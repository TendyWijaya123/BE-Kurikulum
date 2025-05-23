<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER trg_after_insert_instrumen
            AFTER INSERT ON instrumen_penilaian_rps
            FOR EACH ROW
            BEGIN
                UPDATE rps_matakuliah
                SET bobot_penilaian = (
                    SELECT SUM(bobot_penilaian)
                    FROM instrumen_penilaian_rps
                    WHERE rps_id = NEW.rps_id
                )
                WHERE id = NEW.rps_id;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER trg_after_update_instrumen
            AFTER UPDATE ON instrumen_penilaian_rps
            FOR EACH ROW
            BEGIN
                UPDATE rps_matakuliah
                SET bobot_penilaian = (
                    SELECT SUM(bobot_penilaian)
                    FROM instrumen_penilaian_rps
                    WHERE rps_id = NEW.rps_id
                )
                WHERE id = NEW.rps_id;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER trg_after_delete_instrumen
            AFTER DELETE ON instrumen_penilaian_rps
            FOR EACH ROW
            BEGIN
                UPDATE rps_matakuliah
                SET bobot_penilaian = (
                    SELECT IFNULL(SUM(bobot_penilaian), 0)
                    FROM instrumen_penilaian_rps
                    WHERE rps_id = OLD.rps_id
                )
                WHERE id = OLD.rps_id;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_insert_instrumen;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_update_instrumen;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_delete_instrumen;');
    }
};
