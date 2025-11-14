<?php

$json = file_get_contents('table.json');
$data = json_decode($json, true);

function groupByParentId($data): array
{    
    $grouped = [];
    
    foreach ($data as $dir)
    {
        $parent_id = $dir['parent_id'] ?? 'null';
        
        if (!isset($grouped[$parent_id])) {
            $grouped[$parent_id] = [];
        }
        
        $grouped[$parent_id][] = $dir;
    }
    
    return $grouped;
}

function renderTreeRecursive($grouped, $parentId = 'null'): string
{
    if (!isset($grouped[$parentId])) {
        return '';
    }
    
    $html = '<ul>';
    
    foreach ($grouped[$parentId] as $item) 
    {
        $html .= '<li>';
        $html .= htmlspecialchars($item['name']);
        
        $children = renderTreeRecursive($grouped, $item['id']);
        
        if ($children) {
            $html .= $children;
        }
        
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    return $html;
}

$grouped = groupByParentId($data);

echo renderTreeRecursive($grouped, 'null');

?>
