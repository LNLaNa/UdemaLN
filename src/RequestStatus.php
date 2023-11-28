<?php

namespace Src;

enum RequestStatus: string
{
    case PENDING = "Pending";
    case CANCELLED = "Cancelled";
    case STARTED = "Started";
}
