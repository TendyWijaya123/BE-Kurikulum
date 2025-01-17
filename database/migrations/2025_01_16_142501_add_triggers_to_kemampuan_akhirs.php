<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trigger AFTER INSERT
        DB::unprepared("
            CREATE TRIGGER kemampuan_akhirs_after_insert
            AFTER INSERT ON kemampuan_akhirs
            FOR EACH ROW
            BEGIN
                DECLARE total_beban INT;

                SELECT SUM(estimasi_beban_belajar)
                INTO total_beban
                FROM kemampuan_akhirs
                WHERE mata_kuliah_id = NEW.mata_kuliah_id;

                UPDATE mata_kuliahs
                SET 
                    total_beban_belajar = COALESCE(total_beban, 0),
                    sks = COALESCE(total_beban, 0) / 45
                WHERE id = NEW.mata_kuliah_id;
            END;
        ");

        // Trigger AFTER UPDATE
        DB::unprepared("
            CREATE TRIGGER kemampuan_akhirs_after_update
            AFTER UPDATE ON kemampuan_akhirs
            FOR EACH ROW
            BEGIN
                DECLARE total_beban INT;

                SELECT SUM(estimasi_beban_belajar)
                INTO total_beban
                FROM kemampuan_akhirs
                WHERE mata_kuliah_id = NEW.mata_kuliah_id;

                UPDATE mata_kuliahs
                SET 
                    total_beban_belajar = COALESCE(total_beban, 0),
                    sks = COALESCE(total_beban, 0) / 45
                WHERE id = NEW.mata_kuliah_id;
            END;
        ");

        // Trigger AFTER DELETE
        DB::unprepared("
            CREATE TRIGGER kemampuan_akhirs_after_delete
            AFTER DELETE ON kemampuan_akhirs
            FOR EACH ROW
            BEGIN
                DECLARE total_beban INT;

                SELECT SUM(estimasi_beban_belajar)
                INTO total_beban
                FROM kemampuan_akhirs
                WHERE mata_kuliah_id = OLD.mata_kuliah_id;

                UPDATE mata_kuliahs
                SET 
                    total_beban_belajar = COALESCE(total_beban, 0),
                    sks = COALESCE(total_beban, 0) / 45
                WHERE id = OLD.mata_kuliah_id;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS kemampuan_akhirs_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS kemampuan_akhirs_after_update');
        DB::unprepared('DROP TRIGGER IF EXISTS kemampuan_akhirs_after_delete');
    }
};
