<?php
require_once __DIR__ . '/auth.php';
const CMS_ADMINS_FILE = __DIR__ . '/../config/cms-admins.php';
function cms_admins(): array { if(!is_file(CMS_ADMINS_FILE)) return []; $v=require CMS_ADMINS_FILE; return is_array($v)?$v:[]; }
function cms_admin_save(array $admins): bool { $php="<?php\n// CMS administrator accounts. Managed from the CMS.\nreturn ".var_export(array_values($admins),true).";\n"; $tmp=CMS_ADMINS_FILE.'.tmp'; if(file_put_contents($tmp,$php,LOCK_EX)===false)return false; return rename($tmp,CMS_ADMINS_FILE); }
function cms_require_admin(): void { if(empty($_SESSION['cms_admin']['id'])) { header('Location: login.php'); exit; } }
function cms_write_content(array $data): bool { $php="<?php\n// ITC Ratnadipa CMS content. Edit through /cms/.\nreturn ".var_export($data,true).";\n"; $file=__DIR__.'/../config/cms-content.php'; $tmp=$file.'.tmp'; if(file_put_contents($tmp,$php,LOCK_EX)===false)return false; return rename($tmp,$file); }
function cms_rows(string $raw, array $fields): array { $out=[]; foreach(preg_split('/\r?\n/',trim($raw)) as $line){ if(trim($line)==='')continue; $parts=array_map('trim',explode('|',$line)); $row=[]; foreach($fields as $i=>$f)$row[$f]=$parts[$i]??''; $out[]=$row; } return $out; }
function cms_rows_text(array $rows,array $fields): string { $lines=[]; foreach($rows as $r)$lines[]=implode(' | ',array_map(fn($f)=>str_replace(["\r","\n",'|'],' ',(string)($r[$f]??'')),$fields)); return implode("\n",$lines); }
