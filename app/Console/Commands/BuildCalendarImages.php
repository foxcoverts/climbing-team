<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildCalendarImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:build-calendar-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates calendar images for each day of the month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $progress = $this->output->createProgressBar(366);
        $progress->start();

        if (!is_dir('public/images')) {
            mkdir('public/images', 0777, true);
        }

        foreach ($this->months() as $month => $monthName) {
            foreach ($this->days($month) as $day) {
                $svg = $this->svg($monthName, $day);

                if (!is_dir('public/images/dates/' . $month . '/' . $day)) {
                    mkdir('public/images/dates/' . $month . '/' . $day, 0777, true);
                }

                $im = new \Imagick();
                $im->setBackgroundColor(new \ImagickPixel('transparent'));
                $im->readImageBlob($svg);
                $im->setImageFormat('png24');
                $im->despeckleimage();
                $im->writeImage('public/images/dates/' . $month . '/' . $month . '-' . $day . '.png');
                $im->destroy();

                $progress->advance();
            }
        }

        $progress->finish();
    }

    /**
     * Returns a list of month names, keyed by month number.
     *
     * @return array<int,string>
     */
    protected function months(): array
    {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }

    /**
     * Returns a list of days in the given month.
     *
     * @param integer $month
     * @return array<integer>
     */
    protected function days(int $month): array
    {
        return range(1, match ($month) {
            1, 3, 5, 7, 8, 10, 12 => 31,
            2 => 29,
            4, 6, 9, 11 => 30,
        });
    }

    /**
     * Generate SVG for the given month, day and weekday.
     *
     * @param string $month
     * @param integer $day
     * @param string $weekday
     * @return string
     */
    protected function svg(string $month, int $day): string
    {
        return
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 700">' .
            '<g transform="translate(94,94)">' .
            '<path d="M512 455c0 32-25 57-57 57H57c-32 0-57-25-57-57V128c0-31 25-57 57-57h398c32 0 57 26 57 57z" fill="#e0e7ec" />' .
            '<path d="M484 0h-47c2 4 4 9 4 14a28 28 0 1 1-53-14H124c3 4 4 9 4 14A28 28 0 1 1 75 0H28C13 0 0 13 0 28v157h512V28c0-15-13-28-28-28z" fill="#dd2f45" />' .
            '<text id="month" x="256" y="165" fill="#fff" font-family="monospace" font-size="90px" style="text-anchor: middle">' . $month . '</text>' .
            '<text id="day" x="256" y="400" fill="#66757f" font-family="monospace" font-size="256px" style="text-anchor: middle">' . $day . '</text>' .
            '</g>' .
            '</svg>';
    }
}
