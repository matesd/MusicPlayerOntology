<?php

include_once LIBS_DIR . '/arc/ARC2.php';

// SQL database configuration for storing the postings:
$arc_config = array(
  /* MySQL db settings */
    
  'db_host' => 'localhost',
  'db_user' => 'mpo',
  'db_pwd' => 'mpo',
  'db_name' => 'mpo',

  /* ARC2 store settings */
  'store_name' => 'mpo',
  
  /* SPARQL endpoint settings */
  'endpoint_features' => array(
    'select', 'construct', 'ask', 'describe', 
    'load', 'insert', 'delete', 
    'dump' /* dump is a special command for streaming SPOG export */
  ),
  'endpoint_timeout' => 60, /* not implemented in ARC2 preview */
  'endpoint_read_key' => '', /* optional */
  'endpoint_write_key' => '', /* optional */
  'endpoint_max_limit' => 250, /* optional */
);

/* store instantiation */

$store = ARC2::getStore($arc_config);

if (!$store->isSetUp()) {
  $store->setUp(); /* create MySQL tables */
}