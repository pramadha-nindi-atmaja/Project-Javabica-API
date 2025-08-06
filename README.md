  SPESIFICATION
        1 PHP 8.0
        2. Laravel Framework 8.83.12
        3. menggunakan repository pattern design
        4. API Auth ===> Sanctum
 
    -JOURNEY PATTERN
    /app
        1. CREATE MIGRATION

        2. CREATE MODEL
        
        3. CONTROLLER                                              --> business logic
                    /{NamaModule}Controller
                    /FORM VALIDATION {PHP ARTISAN MAKE:REQUEST}
        
        4. REQUEST                                                --> data validation
            HTTP/{NamaModul}
                    /{namamodul}Request
       
        5. ROUTES                                                 --> routing
            API/{GROUPNAME}
                    /{PRIVATE} {MIDDLEWARE}
                    /{PUBLIC}
        
        5. POLICY                                                 --> policy user role and permission
            APP/{NAME}Policy

        6. ADD POLICY In auth service provier                     --> daftarkan ploicy ke authserviceprovider

        7. CREATE REPOSITORIES                                    --> query database
                /Repositories
                    /{NamaModule}Repository
        
        8. CREATE INTERFACE                                       --> create repository contract
                app/Interface
                    /{NamaModule}Interface

        9. REGISTER INTERFACE IN  REPOSITORY SERVICE PROVIDER     --> create repository contract
                app/Interface
                    /{NamaModule}Interface
        
        10. KONFIGURASI PIPELINEFILTER   PADA REPOSITORY          --> query condition
                /PipelineFilter
                    /{namaModule}PipeLine
                        /{pipelineName}

        11. DATA RESOURCE                                        --> data manipulation
            /HTTP
                /{namaModule}Resource
                    /{JenisCollection}Resource
        
    
        //====apabila butuh jobs
    
        SERVICES                                                --> TEMPAT UNTUK INJECT API
                /Services
                    /{NamaModul}Services
                        /{NamaModul}COMMAND {UNTUK INSERT, UPDATE, DETELE HANDLE}
                        /{NamaModul}QUERY   {UNTUK GET HANDLE}
        
        JOBS
            /Jobs
                /{modul}Jobs
                    /{namaTask}Jobs

    -JOB PROCESS
        1. reset password / forgot password --> queuename :  
        2. verify user account              --> queuename :    
        
        -Testing Jobs
            1.php artisan queue:listen
                - hanya berjalan apabila isi dari kolum queuenya default
                - apabila ada spesific namenya harus jalankan namaqueuename nya
            2.php artisan queue:work --queue={nama queuenameny} 
                -spesific ke nama terkait
            3.php artisan queue:work --queue=high,{nama queuenameny} 
                -high di situ menenjukan menjalankan sebagai prioritas

    -FITUR
        1.  login
        2.  forgot password
        3.  reset password
        4.  user profile
        5.  logout
        6.  get scrf token
        7.  create account
        8.  delete user
        9.  edit user
        10. list user
        11. verify user
        12. multi guard (untuk admin dan juga untuk usersweb)
            -untuk liat setup buka page /app/auth.php
            -login juga di buat 2 arah yaitu untuk admin dan juga web
            -untuk  table juga ada 2 yaitu admin dan users
            -sudah di kasih label juga ketika create token sanctum mana yang untuk webdan juga untuk yang admin
                - bisa cek services dan juga repositorynya di app/Repositories/UsersRepository.php
                - untuk ubah signal/flag mana admin dan juga mana user bisa di liat di user repository
        13.role and permission menggunakan spatie
        14. 
    -SEEDER
        1. USER
        2. ADMIN
        3. ROLE
    -SETUP
        1. COMPOSER INSTALL
        2. COPY .ENV.EXAMPLE KE .ENV
        3. php artisan migrate
        4. php artisan key:generate
        5. isi .env yang sesuai 
            - include URL (jgn lupa mailer account beserta frontend url untuk pengembalian token forgot password)
            - database connection
        6. recheck timezone == > config/app.php
        7. ubah QUEUE_CONNECTION=sync MENJADI database
        8. apabila di server install supervisor di ubuntu
    - job 

laravel ini menggunakan repository pattern tahapan untuk membuat module adalah
1.siapkan migrations
2.siapkan routes
3.siapkan request untuk validation
4.siapkan controller
5.siapkan repository
6.siapkan repository interface
7.seting di app.php daftarkan di providers
