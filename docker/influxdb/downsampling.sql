CREATE CONTINUOUS QUERY "downsample_counter_1s_to_10s" ON "metrics"
BEGIN
    SELECT
        sum(value) as sum,
        count(value) as count,
        mean(value) as mean,
        min(value) as lower,
        max(value) as upper,
        percentile(value, 90) as percentile_90,
        percentile(value, 95) as percentile_95,
        stddev(value) as stddev
    INTO "metrics"."10s".:MEASUREMENT
    FROM metrics."1s"./.*/
    WHERE metric_type = 'counter'
    GROUP BY time(10s),*
END;

CREATE CONTINUOUS QUERY "downsample_timing_1s_to_10s" ON "metrics"
BEGIN
    SELECT
        sum(sum) as sum,
        sum(count) as count,
        mean(mean) as mean,
        min(lower) as lower,
        max(upper) as upper,
        mean(percentile_90) as percentile_90,
        mean(percentile_95) as percentile_95,
        mean(stddev) as stddev
    INTO "metrics"."10s".:MEASUREMENT
    FROM metrics."1s"./.*/
    WHERE metric_type = 'timing'
    GROUP BY time(10s),*
END;

CREATE CONTINUOUS QUERY "downsample_histogram_1s_to_10s" ON "metrics"
BEGIN
    SELECT
        sum(sum) as sum,
        sum(count) as count,
        mean(mean) as mean,
        min(lower) as lower,
        max(upper) as upper,
        mean(percentile_90) as percentile_90,
        mean(percentile_95) as percentile_95,
        mean(stddev) as stddev
    INTO "metrics"."10s".:MEASUREMENT
    FROM metrics."1s"./.*/
    WHERE metric_type = 'histogram'
    GROUP BY time(10s),*
END;

CREATE CONTINUOUS QUERY "downsample_10s_to_1m" ON "metrics"
BEGIN
    SELECT
        sum(sum) as sum,
        sum(count) as count,
        mean(mean) as mean,
        min(lower) as lower,
        max(upper) as upper,
        mean(percentile_90) as percentile_90,
        mean(percentile_95) as percentile_95,
        mean(stddev) as stddev
    INTO "metrics"."1m".:MEASUREMENT
    FROM metrics."10s"./.*/
    GROUP BY time(1m),*
END;
 
CREATE CONTINUOUS QUERY "downsample_1m_to_10m" ON "metrics"
BEGIN
    SELECT
        sum(sum) as sum,
        sum(count) as count,
        mean(mean) as mean,
        min(lower) as lower,
        max(upper) as upper,
        mean(percentile_90) as percentile_90,
        mean(percentile_95) as percentile_95,
        mean(stddev) as stddev
    INTO "metrics"."10m".:MEASUREMENT
    FROM metrics."1m"./.*/
    GROUP BY time(10m),*
END;
 
CREATE CONTINUOUS QUERY "downsample_10m_to_1h" ON "metrics"
BEGIN
    SELECT
        sum(sum) as sum,
        sum(count) as count,
        mean(mean) as mean,
        min(lower) as lower,
        max(upper) as upper,
        mean(percentile_90) as percentile_90,
        mean(percentile_95) as percentile_95,
        mean(stddev) as stddev
    INTO "metrics"."1h".:MEASUREMENT
    FROM metrics."10m"./.*/
    GROUP BY time(1h),*
END;
 
CREATE CONTINUOUS QUERY "downsample_1h_to_1d" ON "metrics"
BEGIN
    SELECT
        sum(sum) as sum,
        sum(count) as count,
        mean(mean) as mean,
        min(lower) as lower,
        max(upper) as upper,
        mean(percentile_90) as percentile_90,
        mean(percentile_95) as percentile_95,
        mean(stddev) as stddev
    INTO "metrics"."1d".:MEASUREMENT
    FROM metrics."1h"./.*/
    GROUP BY time(1d),*
END;
