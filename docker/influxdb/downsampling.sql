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
    INTO "metrics"."duration_7d_precision_10s".:MEASUREMENT
    FROM metrics."duration_3d_precision_1s"./.*/
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
    INTO "metrics"."duration_7d_precision_10s".:MEASUREMENT
    FROM metrics."duration_3d_precision_1s"./.*/
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
    INTO "metrics"."duration_7d_precision_10s".:MEASUREMENT
    FROM metrics."duration_3d_precision_1s"./.*/
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
    INTO "metrics"."duration14d_precision_1m".:MEASUREMENT
    FROM metrics."duration_7d_precision_10s"./.*/
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
    INTO "metrics"."duration_90d_precision_10m".:MEASUREMENT
    FROM metrics."duration14d_precision_1m"./.*/
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
    INTO "metrics"."duration_1y_precision_1h".:MEASUREMENT
    FROM metrics."duration_90d_precision_10m"./.*/
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
    INTO "metrics"."duration_inf_precision_1d".:MEASUREMENT
    FROM metrics."duration_1y_precision_1h"./.*/
    GROUP BY time(1d),*
END;
