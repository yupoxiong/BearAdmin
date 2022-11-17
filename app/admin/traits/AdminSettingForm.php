<?php
/**
 * 设置表单操作
 * @author yupoxiong<i@yufuping.com>
 */
namespace app\admin\traits;

use generate\field\Field;

trait AdminSettingForm
{

    protected function getFieldForm($type, $name, $field, $content, $option)
    {
        /** @var Field $fieldClass */
        $fieldClass = '\\generate\\field\\' . parse_name($type === 'switch' ? 'switch_field' : $type, 1, true);
        $form       = $fieldClass::$html;
        switch ($type) {
            case 'switch':
                $content_int = (int)$content;
                $search1     = "{if((!isset(\$data)&&[FIELD_DEFAULT]==1)||(isset(\$data)&&\$data.[FIELD_NAME]==1))}checked{/if}";
                $search2     = "{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}";
                $form        = str_replace(array($search1, $search2, '[ON_TEXT]', '[OFF_TEXT]'), array($content_int ? 'checked' : '', $content_int, '是', '否',), $form);
                break;
            case 'select':
                $option_html = '';
                $option      = explode("\r\n", $option);
                foreach ($option as $item) {
                    $option_key_value = explode('||', $item);

                    $select = '';
                    if ($content === $option_key_value[0]) {
                        $select = 'selected';
                    }
                    $option_html .= '<option value="' . $option_key_value[0] . '" ' . $select . '>' . $option_key_value[1] . '</option>';
                }

                $form = str_replace('[OPTION_DATA]', $option_html, $form);

                break;
            case 'multi_select':
                $option_html = '';
                $option      = explode("\r\n", $option);
                $content = explode(',',$content);
                foreach ($option as $item) {
                    $option_key_value = explode('||', $item);
                    $select = '';
                    if (in_array($option_key_value[0], $content, false)) {
                        $select = 'selected';
                    }
                    $option_html .= '<option value="' . $option_key_value[0] . '" ' . $select . '>' . $option_key_value[1] . '</option>';
                }

                $form    = str_replace('[OPTION_DATA]', $option_html, $form);
                $content = '';
                break;
            case 'image':
                $search1 = "{if isset(\$data)}{\$data.[FIELD_NAME]}{/if}";
                $form    = str_replace(array($search1), array($content), $form);

                break;
            case 'multi_image':
                $search1 = "{\$data.[FIELD_NAME]|default=''}";
                $form    = str_replace(array($search1), array($content), $form);

                break;
            case 'editor':
                $search1 = "{\$data.[FIELD_NAME]|raw|default='[FIELD_DEFAULT]'}";

                $search2  = "{\$data.[FIELD_NAME]|raw|default='<p>[FIELD_DEFAULT]</p>'}";
                if($content===''){
                    $content = '<p></p>';
                }else if(strpos($content,'<p>')!==0){
                    $content = '<p>'.$content.'<p/>';
                }

                $form = str_replace(array($search1, $search2), array($content, $content), $form);

                break;
            case 'map':
                $position = is_string($content) ? explode(',', $content) : $content;
                $lng      = $position[0] ?? 117;
                $lng      = $lng > 180 || $lng < -180 ? 117 : $lng;

                $lat = $position[1] ?? 36;
                $lat = $lat < -90 || $lat > 90 ? 36 : $lat;

                $search1 = "{\$data.[FIELD_NAME_LNG]|default='117'}";
                $search2 = "{\$data.[FIELD_NAME_LAT]|default='36'}";
                $search3 = 'name="[FIELD_NAME_LNG]"';
                $search4 = 'name="[FIELD_NAME_LAT]"';

                $search5 = 'id="[FIELD_NAME_LNG]"';
                $search6 = 'id="[FIELD_NAME_LAT]"';
                $search7 = "$('#[FIELD_NAME_LNG]')";
                $search8 = "$('#[FIELD_NAME_LAT]')";

                $replace4 = $replace3 = 'name="' . $field . '[]"';
                $replace5 = 'id="' . $field . '_lng"';
                $replace6 = 'id="' . $field . '_lat"';
                $replace7 = "$('#" . $field . "_lng')";
                $replace8 = "$('#" . $field . "_lat')";

                $search9 = '[FIELD_NAME_LNG]';


                $form = str_replace(
                    array($search1, $search2, $search3, $search4, $search5, $search6, $search7, $search8, $search9),
                    array($lng, $lat, $replace3, $replace4, $replace5, $replace6, $replace7, $replace8, $field),
                    $form);

                $content = '';
                break;
            default:
                //$form = '';

                break;
        }

        $form_value = "{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}";

        return str_replace(array($form_value, '[FIELD_NAME]', '[FORM_NAME]',), array($content, $field, $name,), $form);

    }

}