<?php

class Parts
{
    static function get_year_combobox()
    {
        $date = getdate();

        for ($i = 2000; $i < 2020; $i++) {
            $selected = $date['year'] === $i ? 'selected' : '';
            echo "<option {$selected} value='{$i}'>{$i}</option>\n";
        }

    }

    static function  get_month_combobox()
    {
        $date = getdate();

        for ($i = 1; $i <= 12; $i++) {
            $selected = $date['mon'] === $i ? 'selected' : '';
            echo "<option {$selected} value='{$i}'>{$i}</option>\n";
        }
    }
}