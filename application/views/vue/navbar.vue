<template>
    <nav class="nav nav-pills nav-fill fixed-bottom border-top border-secondary bg-light j-nav-lg">
        <a class="nav-item nav-link active" href="{{ home }}"><i class="fa fa-home fa-2x"></i></a>
        <a class="nav-item nav-link" href="<?php echo site_url('apps'); ?>"><i class="fa fa-th fa-2x"></i></a>
        {{ if (app) { }}
        <div class="nav-item dropup">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-folder fa-2x"></i></a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#one">one</a>
                <a class="dropdown-item" href="#two">two</a>
                <div role="separator" class="dropdown-divider"></div>
                <a class="dropdown-item" href="#three">three</a>
            </div>
        </div>
        {{ } }}
        <a class="nav-item nav-link" href="{{ myself }}"><i class="fa fa-user fa-2x"></i></a>
    </nav>
</template>

<script>
    modules.exports = {

    }
</script>