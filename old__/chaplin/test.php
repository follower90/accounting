<?php

class Import_csv {
    private $registry;
    public $classObj;
    protected $db;

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = new PDOchild($registry);
        $this->language = 'ru';
    }


    function import_products()
    {

        $settings = Registry::get('user_settings');
        $date=date("Y-m-d H:i:s");

        $_SERVER['HTTP_HOST'] = "gooliver.od.ua";

        send_mime_mail('Gooliver', // имя отправителя
            "info@gooliver.od.ua", // email отправителя
            'admin', // имя получателя
            $settings['email'], // email получателя
            "utf-8", // кодировка переданных данных
            "windows-1251", // кодировка письма
            "Импорт товаров - GOOLIVER.od.ua", // тема письма
            "
			Импорт запущен. {$date}.<br /><br />
			Ожидайте..."
        );

        $cols=$this->select_cols();
        $res = $this->db->rows("SELECT ".$cols['select']." FROM `tmp_products` LIMIT 5000");

        if(count($res) > 0) {

            foreach($res as $row)
            {
                /* Каталоги*/
                /* каталог */

                $url = translit($row[$_POST['catalog']]);
                $catalog = $this->db->row("SELECT `id` FROM `catalog` WHERE `url`=?", array($url));
                if(!isset($catalog['id']))
                {
                    $catalog['id'] = $this->db->insert_id("INSERT INTO `catalog` SET `url`=?, `sub`=?, `active`=?", array($url, NULL, 1));
                    $this->db->query("INSERT INTO `".$this->language."_catalog` SET `cat_id`=?, `name`=?", array($catalog['id'], $row[$_POST['catalog']]));
                }

                /* подкаталог */
                $url = translit($row[$_POST['subcatalog']]);
                $sub_catalog = $this->db->row("SELECT `id` FROM `catalog` WHERE `url`=?", array($url));
                if(!isset($sub_catalog['id']))
                {
                    $sub_catalog['id'] = $this->db->insert_id("INSERT INTO `catalog` SET `url`=?, `sub`=?, `active`=?", array($url, $catalog['id'], 1));
                    $this->db->query("INSERT INTO `".$this->language."_catalog` SET `cat_id`=?, `name`=?", array($sub_catalog['id'], $row[$_POST['subcatalog']]));
                }

                /* Товар */

                if(isset($row[$_POST['code']])&&trim($row[$_POST['code']])!='')
                {
                    $product = $this->db->row("SELECT `id`, `orig_photo` FROM `product` WHERE `code`=?", array($row[$_POST['code']]));

                    if(isset($product['id'])) {
                        $update=$product['id'];
                    } else $update=0;

                    /* наличие */
                    if($row[$_POST['store_status']]=='Есть') {
                        $store = 1;
                    } else {
                        $store = 0;
                    }

                    if($update==0)
                    {
                        $url = translit($row[$_POST['code']]).'-'.translit($row[$_POST['name']]);

                        $q="INSERT INTO product SET
							`date_add`=?, 
							`active`=?,
							`code`=?,
							`code2`=?,
							`price`=?,
							`url`=?,
							`store`=?,
							`waranty`=?";

                        $params = array($date, 1, $row[$_POST['code']], $row[$_POST['code2']], $row[$_POST['price']], $url, $store, $row[$_POST['waranty']]);
                        $update = $this->db->insert_id($q, $params);

                        if((int)$update!=0)
                        {
                            $q="INSERT INTO ".$this->language."_product SET
									`product_id`=?,
									`name`=?,
									`body`=?,
									`body2`=?,
									`body3`=?";

                            $params = array($update, $row[$_POST['name']].' ('.$row[$_POST['code']].')', $row[$_POST['description']], $row[$_POST['complectation']], $row[$_POST['information']]);
                            $this->db->query($q, $params);

                        }
                    }
                    else
                    {
                        $q="UPDATE product SET
								`date_edit`=?,
								`code2`=?,
								`price`=?,
								`store`=?,
								`waranty`=?
								 WHERE id=?";

                        $params = array($date, $row[$_POST['code2']], $row[$_POST['price']], $store, $row[$_POST['waranty']], $update);
                        $this->db->query($q, $params);

                        $q="UPDATE ".$this->language."_product SET
										`name`=?,
										`body`=?,
										`body2`=?,
										`body3`=?
										WHERE `product_id`=?";

                        $params = array($row[$_POST['name']].' ('.$row[$_POST['code']].')', $row[$_POST['description']], $row[$_POST['complectation']], $row[$_POST['information']], $update);
                        $this->db->query($q, $params);
                    }

                    /* фото */

                    if($product['orig_photo'] != $row[$_POST['image_url']])
                    {
                        if(trim($row[$_POST['image_url']])!='')
                        {
                            $dir = createDir($product['id']);
                            $this->SavePicture($row[$_POST['image_url']], $dir[0].$product['id'].'_s.jpg');
                            $this->db->query("UPDATE `product` SET `orig_photo`=? WHERE `id`=?", array($row[$_POST['image_url']], $product['id']));
                        }
                    }
                }


                /* Связка товара с каталогами */
                $incat = $this->db->row("SELECT * FROM `product_catalog` WHERE `product_id`=?", array($update));
                if(! $incat) {
                    $this->db->query("INSERT INTO `product_catalog` SET `catalog_id`=?, `product_id`=?;
									  INSERT INTO `product_catalog` SET `catalog_id`=?, `product_id`=?;", array($catalog['id'], $update, $sub_catalog['id'], $update));
                }


                /* Бренды в фильтры  */

                $url = translit($row[$_POST['vendor']]);
                $brend = $this->db->row("SELECT `id` FROM `params` WHERE `url`=?", array($url));
                if(!isset($brend['id']))
                {
                    $brend['id'] = $this->db->insert_id("INSERT INTO `params` SET `url`=?, `sub`=?, `active`=?", array($url, 199, 1));
                    $this->db->query("INSERT INTO `".$this->language."_params` SET `params_id`=?, `name`=?", array($brend['id'], $row[$_POST['vendor']]));
                }

                /* Связка товара с брендом  */
                $inparam = $this->db->row("SELECT * FROM `params_product` WHERE `product_id`=? AND `params_id`=?", array($update, $brend['id']));
                if(! $inparam) {
                    $this->db->query("INSERT INTO `params_product` SET `params_id`=?, `product_id`=?", array($brend['id'], $update));
                }

                /* Связка каталога с брендом
                (199) - группа фильтров "Производитель"
                */

                $incatalog = $this->db->row("SELECT `catalog_id` FROM `params_catalog` WHERE (`catalog_id`=? OR `catalog_id`=?) AND `params_id`=?", array($catalog['id'], $sub_catalog['id'], 199));

                if(! $incatalog) {
                    $this->db->query("INSERT INTO `params_catalog` SET `catalog_id`=?, `params_id`=?;
							  INSERT INTO `params_catalog` SET `catalog_id`=?, `params_id`=?;", array($catalog['id'], 199, $sub_catalog['id'], 199));
                }

                $this->db->query("DELETE FROM `tmp_products` WHERE `".$_POST['code']."`=?", array($row[$_POST['code']]));
            }

            $this->db->query("UPDATE `cron` SET `active`=?", array(1));


        } else {

            $this->db->query("TRUNCATE TABLE `cron`");

            send_mime_mail('Gooliver', // имя отправителя
                "info@gooliver.od.ua", // email отправителя
                'admin', // имя получателя
                $settings['email'], // email получателя
                "utf-8", // кодировка переданных данных
                "windows-1251", // кодировка письма
                "Импорт товаров завершён - GOOLIVER.od.ua", // тема письма
                "Импорт завершён!"
            );

        }
    }

    function select_cols()
    {
        $return=array();
        $return['select']='';

        $fields = array(
            'id', //ID
            'name', //Наименование
            'vendor', //Производитель
            'catalog', //Категория
            'subcatalog',  //Подкатегория
            'code', //Код
            'code2', //ТНВЭД
            'price', //Цена
            'waranty', //Гарантия (мес.)
            'store_status', //Склад
            'avail_status', //Наличие
            'wait_status', //Ожидается
            'action', //Участвует в акции
            'action_conditions', //Условия акции
            'boxing', //В упаковке
            'boxing_discount', //Товаров со скидкой (повр. упакаковка)

            'description', //Описание
            'complectation', //Комплектация
            'information', //Доп. инфо
            'image_url' //URL изображения
        );


        foreach($fields as $row)
        {
            if(isset($_POST[$row])&&$_POST[$row]!='')$return['select'].=$_POST[$row].',';
        }

        if($return['select']!='')$return['select']=substr($return['select'], 0, strlen($return['select'])-1);
        return $return;
    }

    function set_import($file, $path_to_img='/home/yuma/www/incoming/1c_change/Pictures/')
    {
        $xlsx = new SimpleXLSX($file);

        $result = $xlsx->rows();

        $count_col=count($result[0])-1;
        if($count_col!=0)
        {
            $date = date("Y-m-d H:i:s");
            $this->create_tmp_table($count_col);
            $y=0;
            foreach($result as $row)
            {
                if($y>0)
                {
                    $cols="";
                    for($i=0;$i<=$count_col;$i++)
                    {
                        $cols.="`col".$i."`='".addslashes($row[$i])."'";
                        if($count_col!=$i)$cols.=",";
                    }
                    $this->db->query("INSERT INTO `tmp_products` SET ".$cols);
                }
                else $cols_name=$row;
                $y++;
            }
            return $cols_name;
        }
    }

    function create_tmp_table($count_col)
    {
        $this->db->query("DROP TABLE IF EXISTS `tmp_products`");

        $cols="";
        for($i=0;$i<=$count_col;$i++)
        {
            $cols.="`col".$i."` text DEFAULT NULL,";
        }
        $this->db->query("CREATE TABLE `tmp_products` (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  ".$cols."
							  PRIMARY KEY (`id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
    }

    private function SavePicture($url, $path)
    {
        $ch = curl_init($url);

        /*if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {*/
        $fp = fopen($path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        /*}*/

        fclose($fp);
        curl_close($ch);
    }

    function load_img($id, $path)
    {
        $path777="/home/cycom/public_html/";
        $id = substr($id, 1, strlen($id)-1);
        for($i=1;;$i++)
        {
            $path2=$path.$id.'_'.$i.'.JPEG';
            if(file_exists($path2))
            {
                if($i==1)
                {
                    $dir = createDir($id, $path777);
                    //resizeImage($path2, $dir['0'].$id.".jpg", $dir['0'].$id."_s.jpg", 160, 160);
                    images($path2, $dir[0].$id.".jpg", $dir[0].$id."_s.jpg", 160, 160);
                }
                else{
                    $name = $id.'_'.$i;
                    $insert_id = $this->db->insert_id("INSERT INTO product_photo SET product_id=?, active=?", array($id, 1));

                    $tb=$this->language."_product_photo";
                    $param = array($name, $insert_id);
                    $this->db->query("INSERT INTO `$tb` SET `name`=?, `photo_id`=?", $param);

                    $dir = createDir($id, $path777);
                    //resizeImage($path2, $dir[1].$insert_id.".jpg", $dir[1].$insert_id."_s.jpg", 160, 160);
                    images($path2, $dir[1].$insert_id.".jpg", $dir[1].$insert_id."_s.jpg", 160, 160);
                }
                unlink($path2);
            }
            else break;
        }
    }
}
?>