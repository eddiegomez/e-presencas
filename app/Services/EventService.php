<?php

namespace App\Services;

use App\Models\Event;
use Exception;

class EventService
{

  /**
   * Getting the event correspondig to the specified ID.
   * 
   * @param int $id;
   * @return Event;
   */
  public function getEventById(int $id)
  {
    $event = Event::find($id);
    if (!$event) {
      throw new Exception('O evento não foi encontrado!');
    }

    return $event;
  }
}
