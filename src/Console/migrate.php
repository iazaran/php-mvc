<?php

require_once dirname(__DIR__, 2) . '/env.php';
require_once dirname(__DIR__) . '/App/Database.php';
require_once dirname(__DIR__) . '/migrations.php';

createTables();
