<?php

// On définit une liste de controle d'accès.
// La liste des role ayant le droit d'accéder à chaque route controllée.
// Dans notre implémentation, ne pas indiquer un nom de route dans notre liste de controle permet d'inquer que cette route est publique (accessible par tous) - dans notre cas, la page de login
$acl = [
  "dashboard" => ["admin", "user"],
  
  "Calcul-index" => ["admin", "user"],
  "Calcul-delete" => ["admin", "user"],
  "Calcul-ope" => ["admin", "user"],
  
  "Chantier-index" => ["admin", "user"],
  "Chantier-create" => ["admin", "user"],
  "Chantier-createPost" => ["admin", "user"],
  "Chantier-update" => ["admin", "user"],
  "Chantier-updatePost" => ["admin", "user"],
  "Chantier-delete" => ["admin", "user"],
  
  "Lot-index" => ["admin", "user"],
  "Lot-create" => ["admin", "user"],
  "Lot-createPost" => ["admin", "user"],
  "Lot-update" => ["admin", "user"],
  "Lot-updatePost" => ["admin", "user"],
  "Lot-delete" => ["admin", "user"],
  
  "Insee-index" => ["admin", "user"],
  
  "Doc-index" => ["admin", "user"],
  
  "User-index" => ["admin", "user"],
  
  "Alert-index" => ["admin", "user"],
  
  "Report-index" => ["admin", "user"],
  
];