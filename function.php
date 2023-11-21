<?php
    //получение данных с формы
    $finalYear = $_GET['finalYear'];

    //проверка на правильность ввода года
    if(!empty($finalYear))
    {
        if(is_numeric($finalYear))
        {
            if($finalYear>=2000)
            {
                $integer = (int) $finalYear;
                if(($finalYear - $integer) == 0)
                {
                    if($finalYear < 2500)
                    {
                        decision($finalYear);
                    }
                    else
                    {
                        echo "Не надо вводить год больше 2499";
                    }
                }
                else
                {
                    echo "Введите целое число";
                }
            }
            else
            {
                echo "Мебельный магазин существует с 2000 года!";
            }
        }
        else
        {
            echo"Строка содержит буквы и символы, введите год!";
        }
    }
    else
    {
        echo "Строка пустая, введите год!";
    }
    //решение задачи
    function decision($finalYear)
    {
        $startYear = 1999;
        $month = 0;
        $discountDays[] = 0;
        $tables = 0;
        $chairs = 0;
        $furniture = 0;
        while($startYear < $finalYear)
        {
            $startYear+=1;
            $month = 1;
            while($month!=13)
            {   
                //получаю сколько остаётся дней до пятницы
                $daysUntilFriday = dayOfTheWeek(1,$month,$startYear);

                //числа когда скидка
                $discountDays[$month-1] = 1+$daysUntilFriday;
                //вывод первых пятниц каждого месяца
                //echo "День ". $discountDays[$month-1]. " Месяц ".$month." Год ".$startYear."<br>";
                $month += 1;
            }
            $furniture = outputLogic($discountDays,$startYear,$tables,$chairs);
            $tables+=$furniture;
            $chairs= $chairs + 12-$furniture;
        }
    }
    //дней до пятницы
    function dayOfTheWeek($day,$month,$year)
    {
        //день недели по дате определяю так
        //https://lifehacker.ru/kakoj-den-nedeli/
        $month2 = 0;
        $year2 = 0;
        $decades = 0;
        $dayOfTheWeek = 0;
        $daysUntilFriday = 0;
        //коэффицент месяца
        switch($month)
        {
            case 1: 
                $month2 = 1;
                break;
            case 2: 
                $month2 = 4;
                break;
            case 3: 
                $month2 = 4;
                break;
            case 4: 
                $month2 = 0;
                break;
            case 5: 
                $month2 = 2;
                break;
            case 6: 
                $month2 = 5;
                break;
            case 7: 
                $month2 = 0;
                break;
            case 8: 
                $month2 = 3;
                break;
            case 9: 
                $month2 = 6;
                break;
            case 10: 
                $month2 = 1;
                break;
            case 11: 
                $month2 = 4;
                break;
            case 12: 
                $month2 = 6;
                break;
        }

        //коэф века
        $decades = $year;
        $decades = $decades%100;
        $year = $year - $decades;
        
        switch($year)
        {
            case 2000:
                $year2 = 6;
                break;
            case 2100:
                $year2 = 4;
                break;
            case 2200:
                $year2 = 2;
                break;
            case 2400:
                $year2 = 0;
                break;
        }
        $wholePart = intdiv($decades, 4);
        //код года
        $year2 = ($year2 + $decades + $wholePart) % 7;
        
        $dayOfTheWeek = ($day + $month2 + $year2) % 7;
        
        //считает как на сайте
        //echo "(".$day ." + " . $month2 . " + " . $year2 . ") % 7 = " . $dayOfTheWeek . "<br>";

        //полученный опрядок 0-сб 1-вс 2-пн 3-вт 4-ср 5-чт 6-пт
        switch($dayOfTheWeek)
        {
            case 0:
                $daysUntilFriday = 6;//сб
                break;
            case 1:
                $daysUntilFriday = 5;//вс
                break;
            case 2:
                $daysUntilFriday = 4;//пн
                break;
            case 3:
                $daysUntilFriday = 3;//вт
                break;
            case 4:
                $daysUntilFriday = 2;//ср
                break;
            case 5:
                $daysUntilFriday = 1;//чт
                break;
            case 6:
                $daysUntilFriday = 0;//пт
                break;
        }

        //если високосный год, то январь, февраль + 1 день
        if($year%4==0 && $month<=2)
        {
            $daysUntilFriday++;
        }

        return $daysUntilFriday;
    }
    //вывод данных
    function dateOutput($day,$month,$year)
    {
        switch($month)
        {
            case 1: 
                $month2 = "янв. ";
                break;
            case 2: 
                $month2 = "февр. ";
                break;
            case 3: 
                $month2 = "март ";
                break;
            case 4: 
                $month2 = "апр. ";
                break;
            case 5: 
                $month2 = "май ";
                break;
            case 6: 
                $month2 = "июнь ";
                break;
            case 7: 
                $month2 = "июль ";
                break;
            case 8: 
                $month2 = "авг. ";
                break;
            case 9: 
                $month2 = "сент. ";
                break;
            case 10: 
                $month2 = "окт. ";
                break;
            case 11: 
                $month2 = "ной. ";
                break;
            case 12: 
                $month2 = "дек. ";
                break;
        }
        echo "".$day."-e ".$month." ",$year.'<br>';
        return 0;
    }
    //логика какие пятницы выводить
    function outputLogic($discountDays,$year,$tables,$chairs)
    {
        $furniture = 0;
        $moreСhairs = false;
        $whatOutput = 0;
        //на что делать скидку
        if($tables<$chairs)
        {
            $moreСhairs = true;
        }
        //определяю, в четный или нечетных чаще скидки
        foreach ($discountDays as $value){
            if($value%2==0){
                $furniture++;
            }
        }
        //определяю выводить четные или нечетные
        if($furniture>=6)
        {//в четных чаще скидки
            if($moreСhairs)
            {
                $whatOutput = 0;//выводить четные, т к их больше, а столов меньше
                $tables = $furniture;
            }
            else
            {
                $whatOutput = 1;//выводить нечетные, т к их меньше,а столов больше
                $tables = 12 - $furniture;
            }
        }
        else
        {//в нечетных чаще скидки
            if($moreСhairs)
            {
                $whatOutput = 1;//выводить нечетные, т к их больше, а столов меньше
                $tables = 12 - $furniture;
            }
            else
            {
                $whatOutput = 0;//выводить четные, т к их меньше,а столов больше
                $tables = $furniture;
            }
        }
        for($i = 0; $i<12;$i++)
        {
            //дни, которые надо выводить
            if($discountDays[$i]%2 == $whatOutput){
                dateOutput($discountDays[$i],$i+1,$year);
            }
        }
        return $tables;
    }
    //функция каких скидок больше в некст году
?>