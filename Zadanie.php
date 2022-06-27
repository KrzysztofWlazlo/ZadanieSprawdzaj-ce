<?php

function get_count_of_occurances($array, $key, $index){//Funkcja zliczająca ilość wystąpień imion (index = 0) oraz dat (index = 1).
    $count = 0;
    foreach($array as $entry){
        if($entry[$index] == $key){//Jeśli klucz jest taki sam jak element tablicy w kolumnie index zwiększaj wynik o 1.
            $count++;
        }
    }
    return $count;
}

function get_unique_values($array, $index){//Funkcja pobierająca kolumny danych tablicy bazowej do tablic zawierających tylko imiona lub daty.
    $baza = array();
    foreach($array as $entry){
        array_push($baza, $entry[$index]);//Element z pobranej tablicy z kolumny index wprowadź do tablicy, która po wykonaniu pętli zostanie zwrócona.
    }
    return array_unique($baza);
}

function get_top_10_highest_count($array, $baza, $index){//Funkcja licząca wystąpienia imion (index = 0) oraz dat (index = 1) w tablicach.
    $counter = array();

    foreach($baza as $key){
        $count = get_count_of_occurances($array, $key, $index);
        array_push($counter, [$key, $count]);//Elementy zwrócone z funkcji get_count_of_occurances wprowadź do tablicy.
    }

    $sort = array_column($counter, 1);

    array_multisort($sort, SORT_DESC, $counter);//Sortowanie kolumn zawierających ilość wystąpień.

    return array_slice($counter, 0, 10);//Zwrócenie pierwszych dziesięciu wyników z tablicy (najwyższych).
}


$csv = file('php_internship_data.csv');//Wczytanie pliku php_internship_data.csv
$data = array();

foreach ($csv as $line) {
    $data[] = str_getcsv($line);//Wprowadzenie danych z pliku do tablicy głównej.
}

$baza_imie = get_unique_values($data, 0);//Wprowadzenie kolumny imion do tablicy baza_imię wywołując funkcję get_unique_values w parametrach podając główną tablicę z danymi oraz index kolumny zawierającej imiona.
$top_imie = get_top_10_highest_count($data, $baza_imie, 0);//Wprowadzenie dziesięciu najwyższych wyników wystąpień wraz z przypisanymi im imionami. Jako parametr podane zostają tablica główna wraz z tablicą imion oraz indeksem kolumny imiona w tablicy głównej.

print "<b>Top 10 najczęściej występujących imion:</b><br>";

foreach ($top_imie as $line) {
    $line[0] = mb_convert_case($line[0],MB_CASE_TITLE,'UTF-8');//Zmiana formatu zapisu imienia.
    print "<b> $line[0] </b> wystąpiło <b> $line[1] </b> razy<br>";//Wypisanie wyniku
}

$baza_data = get_unique_values($data, 1);//Wpisanie dat do tablicy analogicznie do tablicy top_imie
$setDate = "2000-01-01";//Data, od której sprawdzane będą daty..
$newTimesArray = array();

foreach ($baza_data as $date) {
    $given_date = new DateTime($date);
    if ($given_date >= new DateTime($setDate)) {//Przeszukanie bazy z datami w poszukiwaniu dat młodszych od tej w zmiennej setDate
        $newTimesArray[] = $date;//Jeśli warunek zostanie spełniony wprowadzenie do tablicy.
    }
}

$top_data = get_top_10_highest_count($data, $newTimesArray, 1, true);//Wprowadzenie dziesięciu najwyższych wyników wystąpień wraz z przypisanymi im datami. Jako parametr podane zostają tablica główna wraz z tablicą dat młodszych od 01-01-2000 oraz indeksem kolumny dat w tablicy głównej.

print "<br><b>Top 10 najczęściej występujących dat urodzenia od 1 stycznia 2000r:</b><br>";

foreach ($top_data as $line) {
    $line[0] = date("d.m.Y", strtotime($line[0]));//Zmiana formatu zapisu daty.
    print "Data <b> $line[0]r </b> wystąpiła <b> $line[1] </b>razy<br>";//Wypisanie wyniku
}

?>

