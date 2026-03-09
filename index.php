<?php
// Conexion a la base de datos
$conn = new mysqli("localhost", "root", "", "pruebas");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

// Insertar datos
if (isset($_POST['insertar'])) {
    $nombre    = $conn->real_escape_string($_POST['nombre']);
    $apellido  = $conn->real_escape_string($_POST['apellido']);
    $telefono  = $conn->real_escape_string($_POST['telefono']);
    $correo    = $conn->real_escape_string($_POST['correo']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $fecha     = $conn->real_escape_string($_POST['fecha']);
    $conn->query("INSERT INTO Cliente(nombre,apellido,telefono,correo,direccion,fecha_registro)
                  VALUES('$nombre','$apellido','$telefono','$correo','$direccion','$fecha')");
    header("Location: index.php"); exit;
}

// Eliminar datos
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM Cliente WHERE id=$id");
    header("Location: index.php"); exit;
}

// Actualizar datos
if (isset($_POST['actualizar'])) {
    $id        = (int)$_POST['id'];
    $nombre    = $conn->real_escape_string($_POST['nombre']);
    $apellido  = $conn->real_escape_string($_POST['apellido']);
    $telefono  = $conn->real_escape_string($_POST['telefono']);
    $correo    = $conn->real_escape_string($_POST['correo']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $fecha     = $conn->real_escape_string($_POST['fecha']);
    $conn->query("UPDATE Cliente SET nombre='$nombre',apellido='$apellido',telefono='$telefono',
        correo='$correo',direccion='$direccion',fecha_registro='$fecha' WHERE id=$id");
    header("Location: index.php"); exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- estilos -->
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #fdf6ee;
            --white:    #fffcf8;
            --border:   #e8ddd1;
            --text:     #2b1f14;
            --muted:    #a8927e;
            --accent:   #c05c2e;
            --accent-h: #a84d22;
            --accent-l: #fdf0e8;
            --danger:   #b83232;
            --danger-l: #fff4f4;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 48px 24px;
        }

        .wrap { max-width: 980px; margin: 0 auto; }

        /* HEADER */
        .site-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--text);
            margin-bottom: 36px;
        }

        .site-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.03em;
        }

        .site-header .fecha {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 300;
        }

        /* BOTONES */
        .btn {
            display: inline-block;
            padding: 7px 16px;
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: 1.5px solid var(--border);
            background: var(--white);
            color: var(--text);
            border-radius: 6px;
            transition: all 0.13s;
        }

        .btn:hover { border-color: var(--muted); background: #faf5ef; }

        .btn-accent {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
        .btn-accent:hover { background: var(--accent-h); border-color: var(--accent-h); color: #fff; }

        .btn-danger { color: var(--danger); border-color: #f0d0d0; }
        .btn-danger:hover { background: var(--danger-l); border-color: #e0aaaa; }

        /* TOP BAR */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .top-bar .total { font-size: 0.8rem; color: var(--muted); }

        /* TABLA */
        .tabla-wrap {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }

        thead tr { background: var(--accent-l); border-bottom: 1.5px solid var(--border); }

        th {
            text-align: left;
            padding: 11px 16px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--accent);
        }

        td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fdf8f3; }

        .id-cell { font-size: 0.78rem; color: var(--muted); }
        .nombre-cell { font-weight: 500; }
        .acciones { display: flex; gap: 6px; }

        .empty { text-align: center; padding: 52px; color: var(--muted); font-size: 0.85rem; }

        /* FORMULARIOS */
        .form-wrap {
            max-width: 440px;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 30px;
        }

        .page-title {
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 26px;
            padding-bottom: 16px;
            border-bottom: 1.5px solid var(--border);
        }

        .campo { margin-bottom: 18px; }

        .campo label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .campo input {
            width: 100%;
            padding: 9px 13px;
            background: var(--bg);
            border: 1.5px solid var(--border);
            color: var(--text);
            font-family: inherit;
            font-size: 0.88rem;
            border-radius: 6px;
            transition: border-color 0.13s;
        }

        .campo input:focus { outline: none; border-color: var(--accent); background: #fff; }
        .campo input::placeholder { color: #d4c4b4; }

        .form-acciones { display: flex; gap: 10px; margin-top: 24px; }

        /* DETALLE */
        .detalle-wrap {
            max-width: 440px;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 30px;
        }

        .detalle-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--accent-l);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 16px;
        }

        .detalle-nombre {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.03em;
            margin-bottom: 4px;
        }

        .detalle-id { font-size: 0.78rem; color: var(--muted); margin-bottom: 24px; }

        .detalle-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 11px 0;
            border-bottom: 1px solid var(--border);
            gap: 16px;
        }

        .detalle-row:last-of-type { border-bottom: none; }
        .detalle-row .lbl { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); white-space: nowrap; }
        .detalle-row .val { font-size: 0.88rem; text-align: right; }

        .detalle-acciones { display: flex; gap: 10px; margin-top: 24px; }
    </style>
</head>
<body>
<div class="wrap">

    <div class="site-header">
        <h1>Gestión de Clientes</h1>
        
    </div>

    <!-- Agregar datos -->
    <?php
    if (isset($_GET['accion']) && $_GET['accion'] === 'agregar'): ?>

        <div class="form-wrap">
            <p class="page-title">Nuevo cliente</p>
            <form method="POST">
                <div class="campo"><label>Nombre</label><input type="text" name="nombre" placeholder="Nombre" required></div>
                <div class="campo"><label>Apellido</label><input type="text" name="apellido" placeholder="Apellido" required></div>
                <div class="campo"><label>Teléfono</label><input type="text" name="telefono" placeholder="000 000 0000"></div>
                <div class="campo"><label>Correo</label><input type="email" name="correo" placeholder="correo@ejemplo.com"></div>
                <div class="campo"><label>Dirección</label><input type="text" name="direccion" placeholder="Dirección"></div>
                <div class="campo"><label>Fecha de registro</label><input type="date" name="fecha"></div>
                <div class="form-acciones">
                    <button type="submit" name="insertar" class="btn btn-accent">Guardar</button>
                    <a href="index.php" class="btn">Cancelar</a>
                </div>
            </form>
        </div>

        <!-- editar datos -->
    <?php elseif (isset($_GET['editar'])):
        $id = (int)$_GET['editar'];
        $result = $conn->query("SELECT * FROM Cliente WHERE id=$id");
        $row = $result->fetch_assoc(); ?>

        <div class="form-wrap">
            <p class="page-title">Editar cliente</p>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="campo"><label>Nombre</label><input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required></div>
                <div class="campo"><label>Apellido</label><input type="text" name="apellido" value="<?= htmlspecialchars($row['apellido']) ?>" required></div>
                <div class="campo"><label>Teléfono</label><input type="text" name="telefono" value="<?= htmlspecialchars($row['telefono']) ?>"></div>
                <div class="campo"><label>Correo</label><input type="email" name="correo" value="<?= htmlspecialchars($row['correo']) ?>"></div>
                <div class="campo"><label>Dirección</label><input type="text" name="direccion" value="<?= htmlspecialchars($row['direccion']) ?>"></div>
                <div class="campo"><label>Fecha de registro</label><input type="date" name="fecha" value="<?= $row['fecha_registro'] ?>"></div>
                <div class="form-acciones">
                    <button type="submit" name="actualizar" class="btn btn-accent">Actualizar</button>
                    <a href="index.php" class="btn">Cancelar</a>
                </div>
            </form>
        </div>

        <!-- ver datos -->
    <?php elseif (isset($_GET['ver'])):
        $id = (int)$_GET['ver'];
        $result = $conn->query("SELECT * FROM Cliente WHERE id=$id");
        $row = $result->fetch_assoc();
        $iniciales = strtoupper(mb_substr($row['nombre'],0,1) . mb_substr($row['apellido'],0,1)); ?>

        <div class="detalle-wrap">
            <div class="detalle-avatar"><?= $iniciales ?></div>
            <p class="detalle-nombre"><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></p>
            <p class="detalle-id">Cliente #<?= $row['id'] ?></p>
            <div class="detalle-row"><span class="lbl">Teléfono</span><span class="val"><?= htmlspecialchars($row['telefono']) ?: '—' ?></span></div>
            <div class="detalle-row"><span class="lbl">Correo</span><span class="val"><?= htmlspecialchars($row['correo']) ?: '—' ?></span></div>
            <div class="detalle-row"><span class="lbl">Dirección</span><span class="val"><?= htmlspecialchars($row['direccion']) ?: '—' ?></span></div>
            <div class="detalle-row"><span class="lbl">Fecha registro</span><span class="val"><?= $row['fecha_registro'] ? date('d/m/Y', strtotime($row['fecha_registro'])) : '—' ?></span></div>
            <div class="detalle-acciones">
                <a href="index.php?editar=<?= $row['id'] ?>" class="btn btn-accent">Editar</a>
                <a href="index.php" class="btn">Volver</a>
            </div>
        </div>

        <!-- ordenar y mostrar datos -->
    <?php else:
        $result = $conn->query("SELECT * FROM Cliente ORDER BY id DESC");
        $totalClientes = $result->num_rows; ?>

        <div class="top-bar">
            <a href="index.php?accion=agregar" class="btn btn-accent">+ Nuevo cliente</a>
            <span class="total"><?= $totalClientes ?> registro<?= $totalClientes !== 1 ? 's' : '' ?></span>
        </div>

        <div class="tabla-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Apellido</th>
                        <th>Teléfono</th><th>Correo</th><th>Fecha</th><th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><span class="id-cell"><?= $row['id'] ?></span></td>
                        <td><span class="nombre-cell"><?= htmlspecialchars($row['nombre']) ?></span></td>
                        <td><?= htmlspecialchars($row['apellido']) ?></td>
                        <td><?= htmlspecialchars($row['telefono']) ?: '—' ?></td>
                        <td><?= htmlspecialchars($row['correo']) ?: '—' ?></td>
                        <td><?= $row['fecha_registro'] ? date('d/m/Y', strtotime($row['fecha_registro'])) : '—' ?></td>
                        <td>
                            <div class="acciones">
                                <a href="index.php?ver=<?= $row['id'] ?>" class="btn">Ver</a>
                                <a href="index.php?editar=<?= $row['id'] ?>" class="btn">Editar</a>
                                <a href="index.php?eliminar=<?= $row['id'] ?>" class="btn btn-danger"
                                   onclick="return confirm('¿Eliminar este cliente?')">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile;
                else: ?>
                    <tr><td colspan="7"><div class="empty">Sin clientes registrados</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>
</body>
</html>