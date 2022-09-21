<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchestra\Parser\Xml\Facade as XmlParser;
use phpQuery;

class Pars extends Model
{
    public function Pars1()
    {
//        Storage::disk('local')->put('https://service.ferroli.ru//storage/equipments/QdKKGApSBYtPGa0Gj0RBiAXGZyhw9DV5TAM3The3.jpeg', 'Contents');

        $xml = XmlParser::load('https://service.ferroli.ru/storage/catalogs/xml-cml.xml')->getContent();
        $characts=$xml->Классификатор->свойства->Свойство;
        $i=0;
        foreach ($characts as $charact)
        {
                $charactArray[(string)$charact->Ид[0]]=(string)$charact->Наименование[0];
        }
        $products=$xml->Каталог->Товары->Товар;
        foreach ($products as $product)
        {
            $values=$product->ЗначениеРеквизита;
            foreach ($values as $value)
            {
                if((string)$value->Наименование[0] === "Цена")
                {
                    $data['price'] = (string)$value->Значение[0];
                }
            }
            $data['tab_name'] = 'Котловое оборудование';
            $data['cat_name'] = 'FERROLI';
            $data['site_url'] = 'https://service.ferroli.ru';
            $data['name'] = (string)$product->Наименование[0];
            $data['description'] = (string)$product->Описание[0];
            $url = (string)$product->Картинка[0];
            $file_extension = pathinfo($url)['extension'];
            $file_name='good/'.Str::random(30).'.'.$file_extension;
            $file = file_get_contents($url);
            Storage::disk('public')->put($file_name, $file);
            $data['image']=str_replace('http://localhost','',Storage::disk('public')->url($file_name));
            $good = Good_p::create($data);
            $good_characts=$product->ЗначенияСвойства;
            foreach ($good_characts as $good_charact)
            {
                $dataCharact['good_p_id']=$good->id;
                $dataCharact['name']=$charactArray[(string)$good_charact->Ид[0]];
                $dataCharact['value']=(string)$good_charact->Значение[0];
                Characteristic_p::create($dataCharact);

            }
        }


    }

    public function Pars2()
    {
        DB::beginTransaction();
        try {
            $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get("https://geffen.ru/product/");
            $String = $Http->body();
            $doc = phpQuery::newDocument($String);
            $entry = $doc->find('div.text ul li a');
            foreach ($entry as $row) {
                $links[] = "https://geffen.ru" . pq($row)->attr('href');
            }
            $links[] = "https://geffen.ru/product/nasosnye_stantsii_podpitki/";
            $links[] = "https://geffen.ru/product/boylery_glb/";
            foreach ($links as $link) {
                $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get($link);
                $String = $Http->body();
                $doc = phpQuery::newDocument($String);
                $entry = $doc->find('div.footer-button a');
                foreach ($entry as $row) {
                    $products[] = "https://geffen.ru" . pq($row)->attr('href');
                }
            }
            foreach ($products as $product) {
                $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get($product);
                $String = $Http->body();
                $data['tab_name'] = 'Котловое оборудование';
                $data['cat_name'] = 'GEFFEN';
                $data['site_url'] = 'https://geffen.ru ';
                $doc = phpQuery::newDocument($String);
                $entry = $doc->find('h1');
                $data['name'] = pq($entry)->text();
                $entry = $doc->find('div.content');

                $data['description'] = pq($entry)->html();
                $data['price'] = 0;

                $entry = $doc->find('a.fancybox');
                foreach ($entry as $row) {
                    $img = pq($row)->attr('href');
                }
                $url = "https://geffen.ru" . $img;
                $file_extension = pathinfo($url)['extension'];
                $file_name = 'good/' . Str::random(30) . '.' . $file_extension;
                $file = file_get_contents($url);
                Storage::disk('public')->put($file_name, $file);
                $data['image'] = str_replace('http://localhost', '', Storage::disk('public')->url($file_name));


                $good = Good_p::create($data);
                $entry = $doc->find('table.props_table td.char_name');
                $charNames = [];
                foreach ($entry as $row) {
                    $charName = pq($row)->text();
                    $charNames[] = preg_replace('/[\t\n]+/', '', $charName);
                }
                $entry = $doc->find('table.props_table td.char_value');
                $charValues = [];
                foreach ($entry as $row) {
                    $charValue = pq($row)->text();
                    $charValues[] = preg_replace('/[\t\n]+/', '', $charValue);
                }
                foreach ($charNames as $key => $charName)
                {
                    $dataCharact['good_p_id']=$good->id;
                    $dataCharact['name']=$charName;
                    $dataCharact['value']=$charValues[$key];
                    Characteristic_p::create($dataCharact);
                    print_r($dataCharact);
                }
            }
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            dd($e);
        }

    }
    public function Pars3()
    {
        DB::beginTransaction();
        try {
            $links[]="https://ekoport.ru/catalog/truboprovodnye_sistemy_vodosnabzheniya_i_otopleniya/truby_pvkh_polivinilkhlorid/";
            for ($i = 2; $i <=16; $i++)
            {
                $links[]="https://ekoport.ru/catalog/truboprovodnye_sistemy_vodosnabzheniya_i_otopleniya/truby_pvkh_polivinilkhlorid/?PAGEN_1=".$i;
            }

            foreach ($links as $link)
            {
                $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get($link);
                $String = $Http->body();
                $doc = phpQuery::newDocument($String);
                $entry = $doc->find('div.item_block a.dark_link');
                foreach ($entry as $row)
                {
                    $products[]="https://ekoport.ru".pq($row)->attr('href');
                }
            }
            foreach ($products as $product)
            {
                $data['tab_name'] = 'Трубы ПВХ';
                $data['cat_name'] = 'Трубы ПВХ';
                $data['site_url'] = 'ekoport.ru/catalog/truboprovodnye_sistemy_vodosnabzheniya_i_otopleniya/truby_pvkh_polivinilkhlorid/';
//                $Http = Http::withoutVerifying()->withHeaders(['Content-Type' => ['text/html; charset=UTF-8']])->withOptions(["verify" => false])->get($product);
                $Http = Http::withoutVerifying()->withHeaders(['Content-Type' => ['text/html; charset=UTF-8']])->withOptions(["verify" => false])->get($product);
                $String = $Http->body();
                $doc = phpQuery::newDocument($String);
                $entry = $doc->find('h1');
                $title=pq($entry)->text();

                $data['name'] = str_replace("( АКЦИЯ! )", "",$title);
                $entry = $doc->find('div.detail_text');
                $description = iconv("windows-1251", "UTF-8", pq($entry)->html());
                $data['description'] = preg_replace('/[\t\n]+/', '', $description);
                $entry = $doc->find('div.prices_block div.price span.price_value');
                $data['price'] = preg_replace("/[^,.0-9]/", '', pq($entry)->text());
                if($data['price']=='')
                {
                    $data['price']=0;
                }
                $good = Good_p::create($data);
                $entry = $doc->find('div.description a');
                foreach ($entry as $row)
                {
                    $filehref=pq($row)->attr('href');
                    $url = "https://ekoport.ru" . $filehref;
                    $file_extension = pathinfo($url)['extension'];
                    $file_name = 'files/' . Str::random(30) . '.' . $file_extension;
                    $file = file_get_contents($url);
                    Storage::disk('public')->put($file_name, $file);
                    $dataCharact['good_p_id']=$good->id;
                    $dataCharact['path']=str_replace('http://localhost', '', Storage::disk('public')->url($file_name));
                    //НУжна для тестов
//                    Storage::disk('public')->delete($file_name);
                    Files_p::create($dataCharact);
                }
                print_r($data);

            }




            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            dd($e);
        }

    }
}
