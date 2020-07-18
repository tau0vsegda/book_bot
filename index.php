<?php

header("Content-Type: text/plain");
 function sendMessage($chat_id, $message) 
 {
 file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
 }
 
 $access_token = '1031635088:AAFb6oGMm5Ph7SrcO3f4H5wr_mXyOq3sRLo';
 $api = 'https://api.telegram.org/bot' . $access_token;
 
 
 $output = json_decode(file_get_contents('php://input'), TRUE);
 $chat_id = $output['message']['chat']['id'];
 $first_name = $output['message']['chat']['first_name'];
 $message = $output['message']['text'];
 
 if ($message == '/start') {
  $preload_text = 'You are welcome, ' . $first_name . '!';
 }
 sendMessage($chat_id, $preload_text);
 //тестовая строка

//include 'cURL';
//$curl = curl_init();

//curl_setopt_array($curl, array(
//    CURLOPT_URL => "https://cdn.animenewsnetwork.com/encyclopedia/api.xml?manga=~jinki",
//    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_FOLLOWLOCATION => true,
//    CURLOPT_ENCODING => "",
//    CURLOPT_MAXREDIRS => 10,
//    CURLOPT_TIMEOUT => 30,
//    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//    CURLOPT_CUSTOMREQUEST => "GET",
//));

//$response = curl_exec($curl);
//$err = curl_error($curl);

//curl_close($curl);

//echo $response;

$temp = simplexml_load_string(<ann>
<manga id="11608" gid="2261423463" type="manga" name="Jinki" precision="manga" generated-on="2020-07-18T08:30:16Z">
<related-prev rel="serialized in" id="4039"/>
<related-next id="4199" rel="sequel"/>
<info gid="2402879141" type="Picture" src="https://cdn.animenewsnetwork.com/thumbnails/fit200x200/encyc/A11608-3.jpg" width="141" height="200">
<img src="https://cdn.animenewsnetwork.com/thumbnails/fit200x200/encyc/A11608-3.jpg" width="141" height="200"/>
<img src="https://cdn.animenewsnetwork.com/thumbnails/max500x600/encyc/A11608-3.jpg" width="334" height="473"/>
</info>
<info gid="2650759617" type="Main title" lang="JA">Jinki</info>
<info gid="3112372728" type="Alternative title" lang="JA">ジンキ ―人機―</info>
<info gid="818174913" type="Genres">science fiction</info>
<info gid="2027426025" type="Number of tankoubon">4</info>
<info gid="523786012" type="Vintage">2000-01-26 to 2001-09-29 (serialized in Gangan Wing)</info>
<ratings nb_votes="1" weighted_score="2"/>
<news datetime="2007-06-26T18:11:46Z" href="https://www.animenewsnetwork.com/news/2007-06-26/sirou-tunasima's-jinki-to-restart-in-dengeki-moeoh">Sirou Tunasima's <cite>Jinki</cite> to Restart in <cite>Dengeki Moeoh</cite></news>
<news datetime="2008-10-27T16:45:05Z" href="https://www.animenewsnetwork.com/news/2008-10-27/jinki-shinsetsu-manga-ends-in-japan"><cite>Jinki -Shinsetsu-</cite> Manga Ends in Japan</news>
<news datetime="2009-03-08T06:51:40Z" href="https://www.animenewsnetwork.com/news/2009-03-08/jinki/extend-new-manga-to-launch-in-may"><cite>Jinki: Extend's</cite> New Manga to Launch in May</news>
<news datetime="2009-04-07T07:49:03Z" href="https://www.animenewsnetwork.com/news/2009-04-07/tales-of-vesperia-film-new-jinki-manga-titled-dated"><cite>Tales of Vesperia</cite> Film, New <cite>Jinki</cite> Manga Titled. Dated</news>
<staff gid="2499743800">
<task>Story & Art</task>
<person id="27743">Sirou Tunasima</person>
</staff>
</manga>
<manga id="4199" gid="3072087439" type="manga" name="Jinki: Extend" precision="manga" generated-on="2020-07-18T08:30:16Z">
<related-prev rel="serialized in" id="3088"/>
<related-prev rel="sequel of" id="11608"/>
<related-next id="4658" rel="adaptation"/>
<info gid="3843124957" type="Picture" src="https://cdn.animenewsnetwork.com/thumbnails/fit200x200/encyc/A4199-3.jpg" width="134" height="200">
<img src="https://cdn.animenewsnetwork.com/thumbnails/fit200x200/encyc/A4199-3.jpg" width="134" height="200"/>
<img src="https://cdn.animenewsnetwork.com/thumbnails/max500x600/encyc/A4199-3.jpg" width="166" height="247"/>
</info>
<info gid="3987665749" type="Main title" lang="EN">Jinki: Extend</info>
<info gid="3003969690" type="Alternative title" lang="JA">ジンキ・エクステンド</info>
<info gid="1246231737" type="Alternative title" lang="ZH-TW">人機・續篇</info>
<info gid="2769200890" type="Genres">action</info>
<info gid="1318876382" type="Genres">science fiction</info>
<info gid="2089018581" type="Themes">mecha</info>
<info gid="2734321190" type="Plot Summary">Akao Hiiragi lives in Japan, and she has lost her entire memory except for three years ago. But one day she meets a mysterious man named Ryohei who tells her that she is a "cognate"; a person with special powers that enable them to pilot robots called Jinki. All across the world, a mysterious phenomenon is occurring, known as the "Lost Life Phenomenon", where an entire town is destroyed from a series of mysterious nuclear-like explosions. Akao discovers that this is not a natural phenomenon, and that it is being caused by a mysterious and evil group called the Hachi Shojin, who are also cognates, and pilot their own Jinkis. Even though she hates to fight, Akao must pilot a Jinki to protect the ones she loves.</info>
<info gid="1813617554" type="Number of tankoubon">9</info>
<info gid="3370032856" type="Number of pages">186</info>
<info gid="2385544912" type="Vintage">2002-02-28 to 2006-06-30 (serialized in Monthly Comic Blade)</info>
<ratings nb_votes="17" weighted_score="4.9086" bayesian_score="5.4491"/>
<release date="2004-07-27" href="https://www.animenewsnetwork.com/encyclopedia/releases.php?id=7287">Jinki: Extend (GN 1)</release>
<release date="2004-08-31" href="https://www.animenewsnetwork.com/encyclopedia/releases.php?id=15338">Jinki: Extend (GN 2)</release>
<release date="2004-11-23" href="https://www.animenewsnetwork.com/encyclopedia/releases.php?id=15339">Jinki: Extend (GN 3)</release>
<news datetime="2007-01-17T15:24:14Z" href="https://www.animenewsnetwork.com/news/2007-01-17/jinki-extend-dropped">Jinki: Extend Dropped From Comic Blade</news>
<news datetime="2007-06-26T18:11:46Z" href="https://www.animenewsnetwork.com/news/2007-06-26/sirou-tunasima's-jinki-to-restart-in-dengeki-moeoh">Sirou Tunasima's <cite>Jinki</cite> to Restart in <cite>Dengeki Moeoh</cite></news>
<news datetime="2008-10-27T16:45:05Z" href="https://www.animenewsnetwork.com/news/2008-10-27/jinki-shinsetsu-manga-ends-in-japan"><cite>Jinki -Shinsetsu-</cite> Manga Ends in Japan</news>
<news datetime="2009-03-08T06:51:40Z" href="https://www.animenewsnetwork.com/news/2009-03-08/jinki/extend-new-manga-to-launch-in-may"><cite>Jinki: Extend's</cite> New Manga to Launch in May</news>
<news datetime="2009-04-07T07:49:03Z" href="https://www.animenewsnetwork.com/news/2009-04-07/tales-of-vesperia-film-new-jinki-manga-titled-dated"><cite>Tales of Vesperia</cite> Film, New <cite>Jinki</cite> Manga Titled. Dated</news>
<staff gid="3938677162">
<task>Story & Art</task>
<person id="27743">Sirou Tunasima</person>
</staff>
</manga>
</ann>);

$jsonTemp = json_encode($temp);
echo $jsonTemp;

//$xml = simplexml_load_string($response);
//$json = json_encode($xml);
//echo $json;
//$array = json_decode($json,TRUE);

//

//test($array);

if ($err) {
 sendMessage($chat_id, "cURL Error #:" . $err);
} else {
 sendMessage($chat_id, $response);
}

sendMessage($chat_id, "я завершил работу");

?>