CREATE TABLE verifyDB (
    row_id INTEGER PRIMARY KEY AUTOINCREMENT,
    id TEXT,
    file_type TEXT,
    verification_result TEXT,
    timestamp DATETIME
);