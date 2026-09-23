<?php

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
