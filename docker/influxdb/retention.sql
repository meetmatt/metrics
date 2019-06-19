DROP retention policy "autogen" on metrics;
CREATE retention policy "1s" on metrics duration 3d replication 1 DEFAULT;
CREATE retention policy "10s" on metrics duration 7d replication 1;
CREATE retention policy "1m" on metrics duration 14d replication 1;
CREATE retention policy "10m" on metrics duration 90d replication 1;
CREATE retention policy "1h" on metrics duration 365d replication 1;
CREATE retention policy "1d" on metrics duration inf replication 1;
