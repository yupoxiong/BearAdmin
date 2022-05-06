<?php
/**
 * 地图选点
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

use generate\exception\GenerateException;

class Map extends Field
{
    public static string $html = <<<EOF
    
<div class="form-group row rowMap">
    <label class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-8 ">
        <div class="input-group mb-3 mapInputContainer">
            <input class="form-control mapKeywords" id="map_key_[FIELD_NAME_LNG]"  placeholder="输入关键字搜索地点" />
        </div>

        <div class="input-group mb-3 mapTipsContainer">
            <span class="form-control mapAddressTips" >
            参考地址：<span id="map_tips_[FIELD_NAME_LNG]" class="mapClipboardSpan" title="点击复制地址" data-toggle="tooltip" data-clipboard-target="#map_tips_[FIELD_NAME_LNG]"></span>
</span>
        </div>
        
        <div id="map_container_[FIELD_NAME_LNG]" class="mapContainer">
        </div>
        <input name="[FIELD_NAME_LNG]" hidden id="[FIELD_NAME_LNG]" value="{\$data.[FIELD_NAME_LNG]|default='117'}">
        <input name="[FIELD_NAME_LAT]" hidden id="[FIELD_NAME_LAT]" value="{\$data.[FIELD_NAME_LAT]|default='36'}" >
    </div>

    
<script>
    AMapUI.loadUI(['misc/PositionPicker'], function(PositionPicker) {
        		
        let defaultLng = "{\$data.[FIELD_NAME_LNG]|default='117'}";
        let defaultLat = "{\$data.[FIELD_NAME_LAT]|default='36'}";
        
        let map_[FIELD_NAME_LNG] = new AMap.Map('map_container_[FIELD_NAME_LNG]', {
            zoom: 16,
            scrollWheel: false,
        })
        let positionPicker = new PositionPicker({
            mode: 'dragMap',
            map: map_[FIELD_NAME_LNG]
        });

        positionPicker.on('success', function(positionResult) {
            console.log(positionResult);
            $('#map_tips_[FIELD_NAME_LNG]').html(positionResult.address);
            console.log('success');
            $('#[FIELD_NAME_LNG]').val(positionResult.position.lng);
            $('#[FIELD_NAME_LAT]').val(positionResult.position.lat);
        });      
        positionPicker.on('fail', function(positionResult) {
            console.log(positionResult);
        });
        if(defaultLng!=='0'){
          positionPicker.start(new AMap.LngLat(defaultLng, defaultLat));  
        }else {
            positionPicker.start();
        }
         
        map_[FIELD_NAME_LNG].panBy(0, 1);
        map_[FIELD_NAME_LNG].addControl(new AMap.ToolBar({
            liteStyle: true
        }));
        // 输入提示
        let autoOptions = {
            input: "map_key_[FIELD_NAME_LNG]"
        };
        let auto = new AMap.Autocomplete(autoOptions);
        let placeSearch = new AMap.PlaceSearch({
            map: map_[FIELD_NAME_LNG]
        });  
        // 构造地点查询类
        auto.on('select',function (e){
            placeSearch.setCity(e.poi.adcode);
            positionPicker.start(new AMap.LngLat(e.poi.location.lng,e.poi.location.lat ));
        });
    });
     var mapClipboard = new ClipboardJS('.mapClipboardSpan');
     mapClipboard.on('success', function(e) {
         layer.tips('地址复制成功！', '#map_tips_[FIELD_NAME_LNG]', {
             tips: [1,]
         });
         console.info('Action:', e.action);
         console.info('Text:', e.text);
         console.info('Trigger:', e.trigger);
         e.clearSelection();
    });

    mapClipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

   
</script>
</div>\n
EOF;

    public static array $rules = [
    ];

    /**
     * @param $data
     * @return string|string[]
     * @throws GenerateException
     */
    public static function create($data): string
    {
        if (strpos($data['field_name'], 'lng') !== false) {
            $data['field_name_lng'] = $data['field_name'];
            $data['field_name_lat'] = str_replace('lng', 'lat', $data['field_name']);
        } else if (strpos($data['field_name'], 'longitude') !== false) {
            $data['field_name_lng'] = $data['field_name'];
            $data['field_name_lat'] = str_replace('longitude', 'latitude', $data['field_name']);
        } else {
            throw new GenerateException('地图字段必须包含lng,lat或longitude,latitude');
        }

        $html = self::$html;
        return str_replace(
            array('[FORM_NAME]', '[FIELD_NAME_LNG]', '[FIELD_NAME_LAT]')
            , array($data['form_name'], $data['field_name_lng']
        , $data['field_name_lat']), $html
        );
    }
}