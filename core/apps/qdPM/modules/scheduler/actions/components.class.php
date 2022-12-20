<?php
/**
* WORK SMART
*/
?>
<?php

class schedulerComponents extends sfComponents
{
  public function executeViewReport()
  {
     $this->events_list = Events::getEventsListByDateQuery(mktime(0, 0, 0, date('m'), date('d'), date('Y')),$this->users_id);        
  }
  
  public function executeViewScheduler()
  {
     
    if(!$this->getUser()->hasAttribute($this->scheduler_time))
    {
      $this->getUser()->setAttribute($this->scheduler_time, time());
    }
            
    $schedulerCurrentTime = $this->getUser()->getAttribute($this->scheduler_time);
  
    // Вычисляем число дней в текущем месяце
    $dayofmonth = date('t', $schedulerCurrentTime);
    
    $currentMonth = date('m', $schedulerCurrentTime);
    $currentYear = date('Y', $schedulerCurrentTime);
    
    // Счётчик для дней месяца
    $day_count = 1;
    // 1. Первая неделя
    $num = 0;
    for($i = 0; $i < 7; $i++)
    {
      // Вычисляем номер дня недели для числа
      $dayofweek = date('w',mktime(0, 0, 0, $currentMonth, $day_count, $currentYear));
      // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
      $dayofweek = $dayofweek - 1;
      if($dayofweek == -1) $dayofweek = 6;
      if($dayofweek == $i)
      {
        // Если дни недели совпадают,
        // заполняем массив $week
        // числами месяца
        $week[$num][$i] = mktime(0, 0, 0, $currentMonth, $day_count, $currentYear);
        $day_count++;
      }
      else
      {
        $week[$num][$i] = "";
      }
    }
    // 2. Последующие недели месяца
    while(true)
    {
      $num++;
      for($i = 0; $i < 7; $i++)
      {
        $week[$num][$i] = mktime(0, 0, 0, $currentMonth, $day_count, $currentYear);
        $day_count++;
        // Если достигли конца месяца - выходим
        // из цикла
        if($day_count > $dayofmonth) break;
      }
      // Если достигли конца месяца - выходим
      // из цикла
      if($day_count > $dayofmonth) break;
    }
    
    $this->week = $week;
  
  }
}
?>
