<?php

if (!function_exists('f_input')) {
    function f_input($opsi, $required = ''): string
    {

        $value = $opsi['value'] ?? '';
        $type = $opsi['type'] ?? 'text';
        $class = $opsi['class'] ?? 'form-control ';
        $class_feedback = $opsi['class_feedback'] ?? 'invalid-feedback';
        $class_div = $opsi['class_div'] ?? 'col-md-12';
        $name = $opsi['name'] ?? '';
        $id = $opsi['id'] ?? $name;
        $id_span = $opsi['id_span'] ?? '';
        $bintang_merah = '';
        if ($required == 'required') {
            $bintang_merah = ' <span class="text text-danger">*</span>';
        }
        $label = $opsi['label'] ?? ucwords(str_replace('_', ' ', $name));
        $span = $opsi['span'] ?? '';
        $pesan = $opsi['pesan'] ?? $label . ' Harus Diisi.';
        $span_display = $span ? '' : 'd-none';
        $p_wrapper = '';
        if ($type == 'password') {
            $span = '👁';
            $class = 'form-control password-input';
            $span_display = ' toggle-password';
            $p_wrapper = 'password-wrapper';
        }

        $feed_back = '';

        if ($class_feedback == 'invalid-feedback') {
            $feed_back = '<div class="' . $class_feedback . '">
                            ' . $pesan . '
                        </div>';
        }


        $input = <<<HTML
                <div class="$class_div">
                    <label for="$id" class="form-label">$label $bintang_merah</label>
                    <div class="input-group has-validation $p_wrapper">
                        <input  type="$type" class="$class" id="$id" name="$name" value="$value" aria-describedby="inputGroupPrepend" $required>
                        <span class="input-group-text $span_display" id="$id_span" style="cursor:pointer;">$span</span>
                        $feed_back
                    </div>
                </div>
                HTML;

        return $input;
    }
}
if (!function_exists('f_option')) {
    function f_option($opsi, $required = '', $encrypted_val = false): string
    {
        $class = $opsi['class'] ?? 'form-select form-control';
        $class_feedback = $opsi['class_feedback'] ?? 'invalid-feedback';
        $class_div = $opsi['class_div'] ?? 'col-md-12';
        $name = $opsi['name'] ?? '';
        $id = $opsi['id'] ?? $name;
        $id_span = $opsi['id_span'] ?? '';
        $bintang_merah = '';
        if ($required == 'required') {
            $bintang_merah = ' <span class="text text-danger">*</span>';
        }
        $label = $opsi['label'] ?? ucwords(str_replace('_', ' ', $name));

        $opt = $opsi['opt'] ?? [];

        $options = '';
        $selected1 = $opsi['selected'] ?? '';
        foreach ($opt as $opt_val => $opt_label) {
            $selected = ($opt_val == $selected1) ? 'selected ' : '';
            $value_opt = $encrypted_val ? encrypt_url($opt_val) : ($opt_val);
            $options .= '<option ' . $selected . '  value="' . ($value_opt)  . '">' . $opt_label . '</option>';
        }
        $opt_awal = $opsi['awal'] ?? [];
        $option1 = '';
        if ($opt_awal) {
            $option1 = '<option value="" selected ' . $opt_awal[1] . '>' . $opt_awal[0] . '</option>';
        }


        $input = <<<HTML
        
                <div class="$class_div">
                    <label for="$id" class="form-label">$label $bintang_merah</label>
                    <select class="$class" name="$name" id="$id" $required>
                        $option1
                        $options
                    </select>
                    <div class="$class_feedback"  id="$id_span">
                        Silahkan Pilih $label .
                    </div>
                </div>
                HTML;

        return $input;
    }
}
if (!function_exists('f_textarea')) {
    function f_textarea($opsi, $required = '')
    {
        $rows = $opsi['rows'] ?? 3;
        $value = $opsi['value'] ?? '';
        $class = $opsi['class'] ?? 'form-control ';
        $class_feedback = $opsi['class_feedback'] ?? 'invalid-feedback';
        $class_div = $opsi['class_div'] ?? 'col-md-12';
        $name = $opsi['name'] ?? '';
        $id = $opsi['id'] ?? $name;
        $id_span = $opsi['id_span'] ?? '';
        $bintang_merah = '';
        if ($required == 'required') {
            $bintang_merah = ' <span class="text text-danger">*</span>';
        }
        $label = $opsi['label'] ?? ucwords(str_replace('_', ' ', $name));
        $span = $opsi['span'] ?? '';
        $span_display = $span ? '' : 'd-none';


        $input = <<<HTML
                <div class="$class_div">
                    <label for="$id" class="form-label">$label $bintang_merah</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text $span_display" id="$id_span">$span</span>
                         <textarea class="$class" name="$name" id="$id" rows="$rows" $required>$value</textarea>
                        <div class="$class_feedback">
                            $label Harus Diisi.
                        </div>
                    </div>
                </div>
                HTML;

        return $input;

        return $input;
    }
}
if (!function_exists('xalert')) {
    function xalert($pesan = '', $opsi = []): string
    {

        $color = $opsi['color'] ?? 'alert-warning';
        $btn_close = $opsi['btn_close'] ?? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

        $alert = <<<HTML
            <div class="alert $color  alert-dismissible fade show" role="alert">
                $pesan
                $btn_close
            </div>
            HTML;

        return $alert;
    }
}
if (!function_exists('alert')) {
    function alert($pesan = '', $opsi = []): string
    {

        $color = $opsi['color'] ?? 'alert-warning';
        $btn_close = $opsi['btn_close'] ?? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $second    = $opsi['second'] ?? 15;
        $alert = <<<HTML
            <div class="alert $color  alert-dismissible fade show" role="alert">
               <strong>$pesan </strong> 
                $btn_close
            </span>
            </div>
                <script>
                startAutoHide({$second});
                </script>
            HTML;

        return $alert;
    }
}
if (!function_exists('swall')) {
    function swall(
        string $title = 'Perhatian!',
        string $text = '',
        string $icon = 'success',
        array $opsi = []
    ): string {

        $title = json_encode($title, JSON_UNESCAPED_UNICODE);
        $text  = json_encode($text, JSON_UNESCAPED_UNICODE);
        $icon  = json_encode($icon);
        $opsi  = json_encode($opsi, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return <<<HTML
        <script>
        swall($title, $text, $icon, $opsi);
        </script>
        HTML;
    }
}
if (!function_exists('alert_environment')) {
    function alert_environment($th): string
    {
        if (ENVIRONMENT == 'production') {
            return alert('Kesalahan Sistem', $opsi = ['color' => 'alert-danger']);
        } else {
            $mes = '';
            $mes .= '<pre>';
            $mes .= 'Message : ' . $th->getMessage() . PHP_EOL;
            $mes .= 'File    : ' . $th->getFile() . PHP_EOL;
            $mes .= 'Line    : ' . $th->getLine() . PHP_EOL;
            $mes .= 'Code    : ' . $th->getCode() . PHP_EOL;
            // $mes .= PHP_EOL;
            // $mes .= $th->getTraceAsString();
            $mes .= '</pre>';
            return  alert($mes, $opsi = ['color' => 'alert-warning']);
        }
    }
}
