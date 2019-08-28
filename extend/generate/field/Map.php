<?php
/**
 * 地图选点
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Map extends Field
{
    public static $html = <<<EOF
    
<div class="form-group">
    <label class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-8 ">
        <div id="map-container" style="width: 100%; height: 350px;position: relative; background-color: rgb(229, 227, 223);overflow: hidden;transform: translateZ(0px);">
        </div>
        <input name="[FIELD_NAME_LNG]" hidden id="[FIELD_NAME_LNG]" value="{\$data.[FIELD_NAME_LNG]|default='[FIELD_DEFAULT_LNG]'}">
        <input name="[FIELD_NAME_LAT]" hidden id="[FIELD_NAME_LAT]" value="{\$data.[FIELD_NAME_LAT]|default='[FIELD_DEFAULT_LAT]'}" >
    </div>
</div>
    
<script>
    AMapUI.loadUI(['misc/PositionPicker'], function(PositionPicker) {
        var map = new AMap.Map('map-container', {
            zoom: 16,
            scrollWheel: true
        })
        var positionPicker = new PositionPicker({
            mode: 'dragMap',
            map: map
        });

        positionPicker.on('success', function(positionResult) {
            console.log(positionResult);
            console.log('success');
            $('#[FIELD_NAME_LNG]').val(positionResult.position.lng);
            $('#[FIELD_NAME_LAT]').val(positionResult.position.lat);
        });
        positionPicker.on('fail', function(positionResult) {
            console.log(positionResult);
        });
        positionPicker.start( 
            {if isset(\$data)}
            new AMap.LngLat({\$data.[FIELD_NAME_LNG]}, {\$data.[FIELD_NAME_LAT]})
            {/if}
            ); 
        map.panBy(0, 1);
        map.addControl(new AMap.ToolBar({
            liteStyle: true
        }))
    });
</script>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'lng_lat'  => '经纬度',
        'regular'  => '自定义正则'
    ];

    public static function create($data)
    {
        if ($data['field_name'] === 'lng') {
            $data['field_name_lng'] = $data['field_name'];
            $data['field_name_lat'] = 'lat';
        } else if ($data['field_name'] === 'longitude') {
            $data['field_name_lng'] = $data['field_name'];
            $data['field_name_lat'] = 'latitude';
        } else {
            throw new \Exception('地图字段必须为lng,lat或longitude,latitude');
        }

        $html = self::$html;
        $html = str_replace(
            array('[FORM_NAME]', '[FIELD_NAME_LNG]', '[FIELD_NAME_LAT]')
            , array($data['form_name'], $data['field_name_lng']
            , $data['field_name_lat']), $html
        );
        return $html;
    }
}