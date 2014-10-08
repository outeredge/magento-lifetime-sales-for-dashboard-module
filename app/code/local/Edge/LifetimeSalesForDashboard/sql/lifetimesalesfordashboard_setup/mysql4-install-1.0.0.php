<?php

$this->startSetup();

$config = new Mage_Core_Model_Config();
$config->saveConfig('sales/lifetime_sales/uid', bin2hex(openssl_random_pseudo_bytes(32)));

$this->endSetup();
