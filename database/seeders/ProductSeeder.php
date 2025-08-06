<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Product_categories;
use App\Models\Product_image;
use App\Models\Product_variant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = 50;
        $sampleDescription = "<p><strong>Description Java Trawas Honey Process 200g Kopi Arabica</strong></p> <p><strong>Java Trawas Honey Process 200g Kopi Arabica</strong>&nbsp;merupakan&nbsp;<em>single origin</em>&nbsp;dari pulau Jawa Indonesia, disangrai pada level&nbsp;<em>light roast</em>&nbsp;untuk lebih mengoptimalkan lagi potensi rasa, ketika proses&nbsp;<em>coffee cupping</em>&nbsp;kami menemukan profil&nbsp;<em>Sugarcane, Stonefruit, Kumquat, Nutmeg</em>. Kreasikan seduhan kopi dengan alat seduh kopi manual favorit temukan ekstraksi rasa optimal pada rasio kopi terbaik Anda, Otten Coffee sangat antusias memberikan pengalaman berharga di tiap seduhan kopi Anda.</p> <p>Sungguh beruntung kami berkerjasama langsung dengan petani kopi lokal mendapatkan panen terbaik yang dipetik teliti hanya buah yang matang.&nbsp;<em>Single origin&nbsp;</em>dari Java Trawas varietas tanaman kopi Lini S dan kartika, tumbuh subur di daerah Mojokerto - Jawa Timur pada ketinggian 1200-1400 mdpl yang termasuk wilayah tanah perhutani dengan nutrisi humus tebal, perawatan organik tanpa bahan kimia. Buah ceri kopi matang dipetik dan hasil panennya diolah secara&nbsp;<em>honey process</em>.</p> <p>Direkomendasikan untuk seduhan kopi filter seperti V60, namun pun demikian metode seduh lainnya juga seru untuk Anda coba. Tunggu apa lagi, pesan segera hanya di ottencoffee.co.id</p> <p>-&nbsp;</p><p>Setiap pesanan bubuk kopi&nbsp;<em>single origin</em>, digiling ketika akan dikemas dan dikirim. Menggunakan mesin penggiling komersial kokoh&nbsp;Mahlkonig VTA 6S&nbsp;tipe&nbsp;<em>conical burr</em>&nbsp;yang terkenal memiliki performa gilingan konsisten dan presisi pada setiap level gilingan.</p> <ul>  <li>Super fine: Turkish coffee</li> <li>Fine: Espresso</li><li>Medium fine: Mokapot</li><li>Medium: Pour over (V60, Chemex, Kalita) syphon, Aeropress, Vietnam Drip</li><li>Medium coarse: French press</li><li>Coarse: Cold drip, cold brew</li> </ul>";
        $sampleinformation = "<p><strong>information Java Trawas Honey Process 200g Kopi Arabica</strong></p> <p><strong>Java Trawas Honey Process 200g Kopi Arabica</strong>&nbsp;merupakan&nbsp;<em>single origin</em>&nbsp;dari pulau Jawa Indonesia, disangrai pada level&nbsp;<em>light roast</em>&nbsp;untuk lebih mengoptimalkan lagi potensi rasa, ketika proses&nbsp;<em>coffee cupping</em>&nbsp;kami menemukan profil&nbsp;<em>Sugarcane, Stonefruit, Kumquat, Nutmeg</em>. Kreasikan seduhan kopi dengan alat seduh kopi manual favorit temukan ekstraksi rasa optimal pada rasio kopi terbaik Anda, Otten Coffee sangat antusias memberikan pengalaman berharga di tiap seduhan kopi Anda.</p> <p>Sungguh beruntung kami berkerjasama langsung dengan petani kopi lokal mendapatkan panen terbaik yang dipetik teliti hanya buah yang matang.&nbsp;<em>Single origin&nbsp;</em>dari Java Trawas varietas tanaman kopi Lini S dan kartika, tumbuh subur di daerah Mojokerto - Jawa Timur pada ketinggian 1200-1400 mdpl yang termasuk wilayah tanah perhutani dengan nutrisi humus tebal, perawatan organik tanpa bahan kimia. Buah ceri kopi matang dipetik dan hasil panennya diolah secara&nbsp;<em>honey process</em>.</p> <p>Direkomendasikan untuk seduhan kopi filter seperti V60, namun pun demikian metode seduh lainnya juga seru untuk Anda coba. Tunggu apa lagi, pesan segera hanya di ottencoffee.co.id</p> <p>-&nbsp;</p><p>Setiap pesanan bubuk kopi&nbsp;<em>single origin</em>, digiling ketika akan dikemas dan dikirim. Menggunakan mesin penggiling komersial kokoh&nbsp;Mahlkonig VTA 6S&nbsp;tipe&nbsp;<em>conical burr</em>&nbsp;yang terkenal memiliki performa gilingan konsisten dan presisi pada setiap level gilingan.</p> <ul>  <li>Super fine: Turkish coffee</li> <li>Fine: Espresso</li><li>Medium fine: Mokapot</li><li>Medium: Pour over (V60, Chemex, Kalita) syphon, Aeropress, Vietnam Drip</li><li>Medium coarse: French press</li><li>Coarse: Cold drip, cold brew</li> </ul>";

        for ($start = 0; $start <= $limit; $start++) {
            $idProd = $start + 1;
            $dataProd = array(
                "id"                  => $idProd,
                "name"                => "product" . $start + 1,
                "is_freeshiping"      => "INACTIVE",
                "slug"                => "product-" . $start + 1,
                "product_description" => $sampleDescription . $start + 1,
                "product_information" => $sampleinformation . $start + 1,
                "meta_keywords"      => "ini adalah meta keyword" . $start + 1,
                "meta_description"   => "ini adalah meta description" . $start + 1,
                "meta_title"    => "ini adalah meta title" . $start + 1,
                "weight"        => "100",
                "type_weight"   => "GRAM",
                "size_long"     => 22,
                "size_tall"     => 74,
                "size_wide"     => 1,
                "type_size"     => "CM",
                "tags"          => "",
                "status"        => "PUBLISH",
                'created_at'    => now(),
                'updated_at'    => now(),
            );

            Product::insertGetId($dataProd);

            //image
            for ($startImage = 0; $startImage < 4; $startImage++) {
                if ($startImage == 0) {
                    $imagePath = 'storage/service_and_experience/160_1666344770_blue-java.jpeg';
                }

                if ($startImage == 1) {
                    $imagePath = 'storage/service_and_experience/622_1666344783_blue-java-label.jpeg';
                }

                if ($startImage == 2) {
                    $imagePath = 'storage/service_and_experience/602_1666344803_blue-java-poster.jpeg';
                }
                if ($startImage == 3) {
                    $imagePath = 'storage/service_and_experience/602_1666344803_blue-java-poster.jpeg';
                }


                $dataImage = array(
                    'id'            => $idProd . $startImage + 1,
                    'fk_product_id' => $idProd,
                    'path'          => $imagePath,
                    'order_number'  => $startImage + 1,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                );

                Product_image::insert($dataImage);
            }

            //variant
            for ($startVariant = 0; $startVariant < 3; $startVariant++) {
                if ($startVariant == 0) {
                    $attributeChild = 9;
                }
                if ($startVariant == 1) {
                    $attributeChild = 10;
                }
                if ($startVariant == 2) {
                    $attributeChild = 11;
                }

                $dataImage = array(
                    'id'                         => $idProd . $startVariant + 1,
                    'sku'                        => 'SKU-PRD-' . $idProd . '-variant-' . $startVariant + 1,
                    'fk_attribute_parent_id'     => 5,
                    'fk_attribute_child_id'      => $attributeChild,
                    'stock'                      => 50,
                    'price'                      => 250000,
                    'discount'                   => null,

                    'fk_product_id'              => $idProd,
                    'image_path'                 => 'storage/service_and_experience/602_1666344803_blue-java-poster.jpeg',
                    'is_ignore_stock'            => 'INACTIVE',
                    'status'                     => "ACTIVE",

                    'created_at'    => now(),
                    'updated_at'    => now(),
                );

                Product_variant::insert($dataImage);
            }


            //category
         
                if ($start >= 0 && $start <= 15) {
                    $category = 12;
                }
                if ($start >= 15 && $start <= 30) {

                    $category = 13;
                }
                if ($start >= 30 && $start <= 50) {

                    $category = 14;
                }

                $dataImage = array(
                    'id'                 => $idProd,
                    'fk_product_id'      => $idProd,
                    'fk_category_id'     => $category,

                );

                Product_categories::insert($dataImage);
           
        }
    }
}
