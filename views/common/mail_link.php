<?php
/** @var $address string|null */
/** @var $label string|null */
?>
<?php
if (is_string($address) && filter_var($address, FILTER_VALIDATE_EMAIL) !== false):
    ?>
    <script type="text/javascript">
        <?php
        $encodedStr = '';
        for ($i = 0; $i < strlen($address); $i++) {
            $char = $address[$i];
            $encodedStr .= '&#' . ord($char) . ';';
        }
        $parts = explode("&#64;", $encodedStr);
        $first = (count($parts) >= 1) ? $parts[0] : '';
        $last = (count($parts) >= 2) ? $parts[1] : '';
        ?>
        var first = '<?php echo $first; ?>';
        var mid = '&#64;';
        var last = '<?php echo $last; ?>';
        document.write(first + mid + last);
    </script>
<?php
endif;
?>