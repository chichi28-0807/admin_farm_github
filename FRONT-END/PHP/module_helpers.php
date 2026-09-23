<?php

function crud_rows(string $dataset, array $defaults): array
{
    if (!isset($_SESSION['farm_crud'][$dataset])) {
        $_SESSION['farm_crud'][$dataset] = $defaults;
    }

    return $_SESSION['farm_crud'][$dataset];
}

function handle_crud_request(string $page): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['crud_page'] ?? '') !== $page) {
        return;
    }

    $dataset = (string) ($_POST['crud_dataset'] ?? '');
    $action = (string) ($_POST['crud_action'] ?? '');
    $index = filter_var($_POST['crud_index'] ?? null, FILTER_VALIDATE_INT);
    $rows = $_SESSION['farm_crud'][$dataset] ?? [];

    if ($dataset === '' || !is_array($rows)) {
        return;
    }

    if ($action === 'delete' && $index !== false && isset($rows[$index])) {
        array_splice($rows, $index, 1);
    } elseif ($action === 'save') {
        $record = [];
        foreach (($_POST['crud_fields'] ?? []) as $field => $value) {
            $record[$field] = trim((string) $value);
        }

        if ($index !== false && isset($rows[$index])) {
            $rows[$index] = array_replace($rows[$index], $record);
        } elseif ($record !== []) {
            $rows[] = $record;
        }
    }

    $_SESSION['farm_crud'][$dataset] = array_values($rows);
}

function crud_value(mixed $value): string
{
    if (is_array($value)) {
        $value = json_encode($value, JSON_UNESCAPED_SLASHES);
    }

    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function render_crud_table(string $title, string $dataset, array $rows, string $page): void
{
    if ($rows === []) {
        return;
    }

    $columns = array_keys($rows[0]);
    echo '<section class="card module-data-section">';
    echo '<header class="card-header"><h2 class="card-title">' . crud_value($title) . '</h2><span class="pill pill-info">' . count($rows) . ' records</span></header>';
    echo '<div class="table-container"><table class="data-table"><thead><tr>';
    foreach ($columns as $column) {
        echo '<th>' . crud_value(ucwords(str_replace('_', ' ', $column))) . '</th>';
    }
    echo '<th>Actions</th></tr></thead><tbody>';
    foreach ($rows as $index => $row) {
        echo '<tr>';
        foreach ($columns as $column) {
            echo '<td>' . crud_value($row[$column]) . '</td>';
        }
        echo '<td class="crud-actions"><details><summary>Edit</summary><form method="post" class="crud-form">';
        echo '<input type="hidden" name="crud_page" value="' . crud_value($page) . '"><input type="hidden" name="crud_dataset" value="' . crud_value($dataset) . '"><input type="hidden" name="crud_action" value="save"><input type="hidden" name="crud_index" value="' . $index . '">';
        foreach ($columns as $column) {
            $value = is_array($row[$column]) ? json_encode($row[$column], JSON_UNESCAPED_SLASHES) : $row[$column];
            echo '<label>' . crud_value(ucwords(str_replace('_', ' ', $column))) . '<input name="crud_fields[' . crud_value($column) . ']" value="' . crud_value($value) . '"></label>';
        }
        echo '<button type="submit" class="btn btn-primary">Save</button></form></details>';
        echo '<form method="post" class="crud-inline-form"><input type="hidden" name="crud_page" value="' . crud_value($page) . '"><input type="hidden" name="crud_dataset" value="' . crud_value($dataset) . '"><input type="hidden" name="crud_action" value="delete"><input type="hidden" name="crud_index" value="' . $index . '"><button type="submit" class="btn btn-danger">Delete</button></form></td></tr>';
    }
    echo '</tbody></table></div>';
    echo '<details class="crud-create"><summary>Add record</summary><form method="post" class="crud-form">';
    echo '<input type="hidden" name="crud_page" value="' . crud_value($page) . '"><input type="hidden" name="crud_dataset" value="' . crud_value($dataset) . '"><input type="hidden" name="crud_action" value="save"><input type="hidden" name="crud_index" value="-1">';
    foreach ($columns as $column) {
        echo '<label>' . crud_value(ucwords(str_replace('_', ' ', $column))) . '<input name="crud_fields[' . crud_value($column) . ']"></label>';
    }
    echo '<button type="submit" class="btn btn-primary">Create record</button></form></details></section>';
}

function render_data_table(string $title, array $rows): void
{
    if ($rows === []) {
        return;
    }

    $columns = array_keys($rows[0]);
    echo '<section class="card module-data-section">';
    echo '<header class="card-header"><h2 class="card-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2><span class="pill pill-info">' . count($rows) . ' records</span></header>';
    echo '<div class="table-container"><table class="data-table"><thead><tr>';
    foreach ($columns as $column) {
        echo '<th>' . htmlspecialchars(ucwords(str_replace('_', ' ', $column)), ENT_QUOTES, 'UTF-8') . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($rows as $row) {
        echo '<tr>';
        foreach ($columns as $column) {
            $value = $row[$column];
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }
            echo '<td>' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody></table></div></section>';
}
