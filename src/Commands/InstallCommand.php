<?php
/**
 * Created by PhpStorm.
 * User: Furkan
 * Date: 9.9.2015
 * Time: 22:10
 */

namespace Whole\Core\Commands;

use kcfinder\file;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallCommand extends Command
{
    protected $name = 'whole:install';
    protected $description = 'Whole CMS Install';

    public function handle()
    {
        //App\Providers\RouteServiceProvider::class,
        //'View'      => Illuminate\Support\Facades\View::class,
        $this->info("
\########################################################/
 \   __             __  _______    _   _    _______     /
  \  \ \           / / / _____/   / \ / \  /  ____/    /
   \  \ \   ____  / / / /        /       \ \ /_____   /
    \  \ \ / /\ \/ / /  \____   /  /\/\   \ ___/  /  /
     \  \___/  \__/  \_______/ /__/    \___\\_____/  /
      \                                            /
       \##########################################/
------------------------------------------------------------
        ");

        $this->info("Devam Etmeden Once ENV Dosyasinin Olusmus Oldugunu ve Ayarlarinin Yapildigina Emin Olun Bundan Sonraki Adimlar Icin Veri Tabani Ayarlari Yapilmis Olmalidir.");
        $isOK = $this->confirm('Veri Tabani Ayarlari Yapildimi?',false);
        if (!$isOK)
        {
            $this->info("Veri Tabani Ayarlarini Yaptiktan Sonra Komutu Calistirin.");
            return false;
        }


        $env = base_path('.env');
        file_put_contents($env,file_get_contents($env)."\rANALYTICS_SITE_ID=\rANALYTICS_CLIENT_ID=\rANALYTICS_SERVICE_EMAIL=");
        $this->info("ENV Dosyasi Analytics Anahtarlari Eklendi.");

        $app_file_path = base_path('config/app.php');
        $app_file = file($app_file_path);
        foreach($app_file as $line)
        {
            $lines[] = $line;
            if (trim($line)=="Whole\Core\CoreServiceProvider::class,")
            {
                $lines[] = "\t\t".'//include components providers'."\n";
            }

            if (trim($line)=="'View'      => Illuminate\Support\Facades\View::class,")
            {
                $lines[] = "\t\t".'//include components aliases'.",\n";
            }

        }
        file_put_contents($app_file_path,$lines);
        unset($lines);
        $this->info("Providers ve Alias Anahtarlari Eklendi.");

        exec("php ".base_path("artisan")." vendor:publish",$vendors);
        if (is_array($vendors))
        {
            foreach ($vendors as $vendor)
            {
                $this->info($vendor);
            }

        }else
        {
            $this->info($vendors);
        }
        $this->info("Public Dosyalari Birlestirildi.");

        $this->info("Tablolar Yüklenirken Bekleyin....");
        exec("php ".base_path("artisan")." migrate",$migrates);
        if (is_array($migrates))
        {
            foreach($migrates as $migrate)
            {
                $this->info($migrate);
            }
        }else
        {
            $this->info($migrates);
        }
        $this->info("Tablolar Migrate Edildi.");

        exec("cd .. && composer dump-autoload",$composers);
        if (is_array($composers))
        {
            foreach ($composers as $composer)
            {
                $this->info($composer);
            }
        }else
        {
            $this->info($composers);
        }
        $this->info('Composer Dump Autoload Edildi');

        exec("php ".base_path("artisan")." db:seed",$seeds);
        if (is_array($seeds))
        {
            foreach ($seeds as $seed)
            {
                $this->info($seed);
            }
        }else
        {
            $this->info($seeds);
        }
        $this->info('Veri Tabanı İçerikleri Aktarıldı');


        $name = $this->ask('Yönetici Adı');

        while(1)
        {
            $email = $this->ask('Yönetici E-Mail Adresi');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->info("Lütfen Geçerli Bir Mail Adresi Giriniz!");
            }else
            {
                break;
            }
        }
        $password = $this->ask('Yönetici Şifresi');

        $user = DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        DB::table('user_role')->insert([
            'user_id'=>$user->id,
            'role_id'=>'1',
        ]);

        $this->info("Yönetici Başarıyla Oluşturuldu");

        $title = $this->ask("Site Başlığı","Whole CMS");

        DB::table('settings')->insert([
            'title'=>$title,
            'status'=>1,
            'copyright'=>'Powered by <a target="_blank" href="http://swbilisim.com">SW Bilişim</a>',
        ]);
        $this->info("Site Ayarlari Yapilandirildi");
        $this->info("CMS Basariyla Yuklendi");
        $this->info("Tesekkur Ederiz.");
        $this->info("-------------------------------------------------");
        $this->info("-------------------------------------------------");
        $this->info("-------------------------------------------------");
        $this->info("
##################################################################
########   Arka Uca Erismek Icin siteadresi.com/admin   ##########
Yonetici E-Madil Adresi:
{$email}
Sifreniz:
{$password}
##################################################################
##################################################################
                Authors:
                https://github.com/furkancelik
                https://github.com/onerciller
                https://github.com/wholecms
##################################################################
##################################################################
");

    }
}