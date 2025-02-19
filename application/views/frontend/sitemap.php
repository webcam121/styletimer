<?= '<?xml version="1.0" encoding="UTF-8" ?>' ?>

<urlset xmlns="<?php echo base_url('schemas/sitemap'); ?>">
    <url>
        <loc><?= base_url();?></loc> 
        <priority>1.0</priority>
    </url>

    <!-- My code is looking quite different, but the principle is similar -->
    <?php foreach($userdata as $row) { ?>
    <url>
        <?php if ($row->slug !== '') : ?>
            <loc><?= base_url('salons/'.$row->slug.'/'.$row->city) ?></loc>
        <?php elseif (isset($row->id)) : ?>
            <loc><?= base_url('salons/?id='. url_encode($row->id)) ?></loc>
        <?php endif; ?>
        <priority>1.0</priority>
    </url>
    <?php } ?>
    
     <?php foreach($otherUrl as $url) { ?>
    <url>
        <loc><?= base_url($url) ?></loc>
        <priority>1.0</priority>
    </url>
    <?php } ?>
    
    <?php foreach($category as $cat) { ?>
    <url>
        <loc><?= base_url('listing/search/'.create_slug_without_db($cat->category_name).'?category='.url_encode($cat->id)); ?></loc>
        <priority>1.0</priority>
    </url>
    <?php } ?>

</urlset>
