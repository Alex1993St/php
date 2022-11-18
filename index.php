<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$param = $_GET;
$data = new Item();
$items = $data->getInfoAndProperty($param);
$pagination = $data->pagination($param);
$types = $data->getType();
$towns = $data->getTown();
$href = $data->getHref();
?>

<html>
<body>
<form>
    <select name="town">
        <option value=""></option>
        <?php foreach ($towns as $town): ?>
            <option value="<?= $town['id'] ?>" <?= isset($param['town']) && $param['town'] == $town['id']  ? 'selected' : '' ?>><?= $town['town'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="number" value="<?= isset($param['number']) ? $param['number'] : '' ?>"
           placeholder="Number of Bedrooms">
    <input type="text" name="price_from" value="<?= isset($param['price_from']) ? $param['price_from'] : '' ?>" placeholder="Price from">
    <input type="text" name="price_to" value="<?= isset($param['price_to']) ? $param['price_to'] : '' ?>" placeholder="Price to">
    <input type="text" name="property_type" value="<?= isset($param['property_type']) ? $param['property_type'] : '' ?>"
           placeholder="Property Type">
    <select name="type">
        <option value=""></option>
        <?php foreach ($types as $type): ?>
            <option value="<?= $type['id'] ?>" <?= isset($param['type']) && $param['type'] == $type['id']  ? 'selected' : '' ?>><?= $type['type'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<?php foreach ($items as $item): ?>
    <div>
        <p>Uuid: <?= isset($item['uuid']) ? $item['uuid'] : '' ?> </p>
        <p>Town: <?= isset($item['town']) ? $item['town'] : '' ?> </p>
        <p>Number of Bedrooms: <?= isset($item['num_bedrooms']) ? $item['num_bedrooms'] : '' ?> </p>
        <p>Price: <?= isset($item['price']) ? $item['price'] : '' ?> </p>
        <p>Property Type: <?= isset($item['title']) ? $item['title'] : '' ?> </p>
        <p>For Sale / For Rent: <?= isset($item['type']) ? $item['type'] : '' ?> </p>
        <p>County: <?= isset($item['county']) ? $item['county'] : '' ?> </p>
        <p>Country: <?= isset($item['country']) ? $item['country'] : '' ?> </p>
        <p>Description: <?= isset($item['description']) ? $item['description'] : '' ?> </p>
        <p>Address: <?= isset($item['address']) ? $item['address'] : '' ?> </p>
        <p>Image full: <?= isset($item['image_full']) ? $item['image_full'] : '' ?> </p>
        <p>Image thumbnail: <?= isset($item['image_thumbnail']) ? $item['image_thumbnail'] : '' ?> </p>
        <p>Latitude: <?= isset($item['latitude']) ? $item['latitude'] : '' ?> </p>
        <p>Longitude: <?= isset($item['longitude']) ? $item['longitude'] : '' ?> </p>
        <p>Num bathrooms: <?= isset($item['num_bathrooms']) ? $item['num_bathrooms'] : '' ?> </p>
        <p>Created at: <?= isset($item['created_at']) ? $item['created_at'] : '' ?> </p>
        <p>Updated at: <?= isset($item['updated_at']) ? $item['updated_at'] : '' ?> </p>
        <hr/>
    </div>
<?php endforeach; ?>

<?php for ($i = 0; $i < $pagination; $i++): ?>
    <a href="<?= $href ?>page=<?= $i + 1 ?>"><?= $i + 1 ?> | </a>
<?php endfor; ?>
</body>
</html>



