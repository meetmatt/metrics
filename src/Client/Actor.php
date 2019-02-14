<?php

namespace Client;

class Actor
{
    // time resolution for simulating time flow = 1:144, 1 client day = 10 real minutes
    // start with current time with offset in timezone randomly +-2 hours (center of America).
    // measure microseconds spent in state, multiply it by 144 and add to current time
    // each loop increases or decreases probability of some type of event happening
    // closer to the lunch - chance of delay increases exponentially
    // further from lunch - chance of delay decreases exponentially
    // etc.

    // once in a while the actor will probabilistically transition to one of the following states:
    // - clear cookie = 0.02
    //   aka deleted the token, same as if it would have expired on the server
    // - change device after big (X seconds) delay = 0.2
    //   changed to previously used device which has a cookie with token or to a completely new device
    //   -> go forward to where initially wanted and fail auth, so will have to re-login with credentials
    // - forgot all credentials immediately = 0.05
    //   -> skip forward where wanted, but later won't be able to ever login again, have to register again
    // - forgot password = 0.05
    //   -> skip until have to re-login, try login with random password for N times, after that login valid password
    //   -> N is rand(1, 10), each time chance to transition to forgot credentials += 0.1
    // - delay in usage, doing something else, not using the service, e.g. driving/working/eating/sleeping/etc.
    //   -> delay probability and length can be influenced by absolute time and calendar
    //   -> e.g. night, weekend, rush hour, lunch time, Christmas
    // ToDO: how to chose one event from a set of events with absolute isolated probability (sum/divide?)
}