<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}

	public function sendSms2Manager($zone_id){
        $query = $this->db->query("SELECT phone FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' ");
        $zone_info = $query->row;
        if ($zone_info["phone"]){
            $curl = curl_init();
            $url = "https://smsc.ru/sys/send.php?login=glinda&psw=vfhfpv1972";
            $url .= "&phones=".$zone_info["phone"];
            $url .= "&mes=Создан+заказ+в+вашей+зоне+ответственности";
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = curl_exec($curl);
            curl_close($curl);
        }
    }

    public function getStoreByZoneid($zone_id){
	    $res = "000005"; //по умолчанию
	    switch ($zone_id){
            case 4237: //Новосибирск, Первомайский район
                $res = "000020";
                break;
            case 4243: //Дзержинский
            case 4244: //Центральный
            case 4245: //Железнодорожный
            case 4241: //Ленинский
            case 4242: //Октябрьский
                $res = "000020";
                break;

            case 4240: //Заельцовский
            case 4239: //Калининский
                $res = "000025";
                break;
            case 4246: //Новосибирск, Васхнил
                $res = "000022";
                break;
        }
        return $res;
    }

}