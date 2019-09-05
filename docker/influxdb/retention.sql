DROP retention policy "autogen" on metrics;
CREATE retention policy "duration_3d_precision_1s" on metrics duration 3d replication 1 DEFAULT;
CREATE retention policy "duration_7d_precision_10s" on metrics duration 7d replication 1;
CREATE retention policy "duration_14d_precision_1m" on metrics duration 14d replication 1;
CREATE retention policy "duration_90d_precision_10m" on metrics duration 90d replication 1;
CREATE retention policy "duration_1y_precision_1h" on metrics duration 365d replication 1;
CREATE retention policy "duration_inf_precision_1d" on metrics duration inf replication 1;
