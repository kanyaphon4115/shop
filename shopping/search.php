<?php
session_start();
require_once __DIR__ . '/../config/connection.php';

$query = trim($_GET['q'] ?? '');
$category = strtolower(trim($_GET['category'] ?? 'all'));
$allowedCategories = ['all', 'men', 'women', 'kids', 'sneakers', 'sale'];
if (!in_array($category, $allowedCategories, true)) {
    $category = 'all';
}

$term = '%' . $query . '%';
$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE (LOWER(p.name) LIKE LOWER(?)
            OR LOWER(COALESCE(c.name, '')) LIKE LOWER(?)
            OR LOWER(COALESCE(p.brand, '')) LIKE LOWER(?)
            OR LOWER(COALESCE(p.description, '')) LIKE LOWER(?))";

if (in_array($category, ['men', 'women', 'kids'], true)) {
    $sql .= ' AND p.audience = ?';
} elseif ($category === 'sneakers') {
    $sql .= " AND LOWER(COALESCE(c.name, '')) = 'sneakers'";
} elseif ($category === 'sale') {
    $sql .= ' AND p.on_sale = 1';
}
$sql .= ' ORDER BY p.id ASC';

$stmt = mysqli_prepare($conn, $sql);
if (in_array($category, ['men', 'women', 'kids'], true)) {
    mysqli_stmt_bind_param($stmt, 'sssss', $term, $term, $term, $term, $category);
} else {
    mysqli_stmt_bind_param($stmt, 'ssss', $term, $term, $term, $term);
}
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$resultCount = mysqli_num_rows($results);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$categoryLabels = ['all' => 'All Categories', 'men' => 'Men', 'women' => 'Women', 'kids' => 'Kids', 'sneakers' => 'Sneakers', 'sale' => 'Sale'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Search<?php echo $query !== '' ? ' - ' . e($query) : ''; ?> | SPARK</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<header class="bg-white shadow">
<div class="flex w-full items-center justify-between px-4 py-4 md:px-10">
<a href="../index.php" class="text-3xl font-bold">SPARK</a>
<form action="search.php" method="get" class="ml-5 flex min-w-0 flex-1 md:w-1/2 md:flex-none">
<select name="category" class="max-w-32 rounded-l border px-2 py-2 md:max-w-none md:px-3" aria-label="Product category">
<?php foreach ($categoryLabels as $value => $label): ?><option value="<?php echo $value; ?>" <?php echo $category === $value ? 'selected' : ''; ?>><?php echo $label; ?></option><?php endforeach; ?>
</select>
<input name="q" value="<?php echo e($query); ?>" class="min-w-0 flex-1 border px-3 py-2 md:px-4" placeholder="Search for more than 20,000 products" aria-label="Search products">
<button type="submit" class="rounded-r bg-blue-500 px-4 text-white transition hover:bg-blue-600 md:px-6" aria-label="Search">🔍</button>
</form>
</div>
</header>
<nav class="overflow-x-auto border-t bg-white"><div class="min-w-[720px]"><?php include __DIR__ . '/../includes/navbar.php'; ?></div></nav>

<main class="mx-auto max-w-7xl px-4 py-10 md:px-10">
<div class="mb-7">
<h1 class="text-2xl font-bold">Search results for: “<?php echo e($query); ?>”</h1>
<p class="mt-2 text-sm text-gray-500"><?php echo $resultCount; ?> product<?php echo $resultCount === 1 ? '' : 's'; ?> in <?php echo e($categoryLabels[$category]); ?></p>
</div>

<?php if ($resultCount === 0): ?>
<div class="rounded bg-white px-5 py-16 text-center shadow-sm">
<div class="text-5xl">⌕</div>
<h2 class="mt-4 text-xl font-semibold">No products found for “<?php echo e($query); ?>”</h2>
<p class="mt-2 text-gray-500">Try another keyword or choose a different category.</p>
</div>
<?php else: ?>
<div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4">
<?php while ($product = mysqli_fetch_assoc($results)): ?>
<a href="product_detail.php?id=<?php echo (int) $product['id']; ?>" class="flex h-full flex-col rounded bg-white p-4 shadow transition hover:-translate-y-1 hover:shadow-lg">
<img src="../assets/images/<?php echo e($product['image']); ?>" class="aspect-square w-full object-contain" alt="<?php echo e($product['name']); ?>">
<h2 class="mt-3 min-h-10 text-sm font-semibold"><?php echo e($product['name']); ?></h2>
<p class="mt-2 text-xs text-gray-500"><?php echo e($product['brand']); ?> · <?php echo e($product['category_name']); ?></p>
<p class="mt-auto pt-2 font-bold text-green-600">$<?php echo number_format((float) $product['price'], 2); ?></p>
<div class="text-sm text-yellow-400">★★★★★ <span class="text-gray-500"><?php echo number_format((float) $product['rating'], 1); ?></span></div>
</a>
<?php endwhile; ?>
</div>
<?php endif; ?>
</main>
</body>
</html>
