<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get("https://geffen.ru/product/");
        $String = $Http->body();
        $doc = phpQuery::newDocument($String);
        $entry = $doc->find('div.text ul li a');
        foreach ($entry as $row) {
            $links[] = "https://geffen.ru".pq($row)->attr('href');
        }
        $links[]="https://geffen.ru/product/nasosnye_stantsii_podpitki/";
        $links[]="https://geffen.ru/product/boylery_glb/";
        foreach ($links as $link)
        {
            $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get($link);
            $String = $Http->body();
            $doc = phpQuery::newDocument($String);
            $entry = $doc->find('div.footer-button a');
            foreach ($entry as $row) {
                $products[] = "https://geffen.ru".pq($row)->attr('href');
            }
        }
        foreach ($products as $product)
        {
            $Http = Http::withoutVerifying()->withOptions(["verify" => false])->get($product);
            $String = $Http->body();
            dd($String);
            $data['tab_name'] = 'Котловое оборудование';
            $data['cat_name'] = 'GEFFEN';
            $data['site_url'] = 'https://geffen.ru ';
            $doc = phpQuery::newDocument($String);
            $entry = $doc->find('h1');
            dd(pq($entry)->text());
            $data['name'] = pq($entry)->text();
            $entry = $doc->find('div.content');

            $data['description'] = pq($entry)->html();
            $data['price'] = 0;
            $entry = $doc->find('div.flex-viewport');
            dd(pq($entry)->html());
            foreach ($entry as $row)
            {
                $img[] =$row->html();
            }
            dd($img);

        }


    }
}
