<?php

namespace Database\Seeders;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dispatcher = User::create([
            'name' => 'Диспетчер',
            'email' => 'dispatcher@test.local',
            'password' => Hash::make('password'),
            'role' => 'dispatcher',
        ]);

        $master1 = User::create([
            'name' => 'Мастер 1',
            'email' => 'master1@test.local',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        $master2 = User::create([
            'name' => 'Мастер 2',
            'email' => 'master2@test.local',
            'password' => Hash::make('password'),
            'role' => 'master',
        ]);

        RepairRequest::create([
            'client_name' => 'Иванов Иван',
            'phone' => '+7 900 111-22-33',
            'address' => 'ул. Ленина, д. 1',
            'problem_text' => 'Не работает холодильник',
            'status' => 'new',
        ]);

        RepairRequest::create([
            'client_name' => 'Петрова Мария',
            'phone' => '+7 900 222-33-44',
            'address' => 'ул. Мира, д. 5',
            'problem_text' => 'Сломалась стиральная машина',
            'status' => 'new',
        ]);

        RepairRequest::create([
            'client_name' => 'Сидоров Пётр',
            'phone' => '+7 900 333-44-55',
            'address' => 'пр. Победы, д. 10',
            'problem_text' => 'Не включается телевизор',
            'status' => 'assigned',
            'assigned_to' => $master1->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Козлова Анна',
            'phone' => '+7 900 444-55-66',
            'address' => 'ул. Гагарина, д. 15',
            'problem_text' => 'Течёт кран на кухне',
            'status' => 'in_progress',
            'assigned_to' => $master1->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Морозов Алексей',
            'phone' => '+7 900 555-66-77',
            'address' => 'ул. Советская, д. 20',
            'problem_text' => 'Не греет водонагреватель',
            'status' => 'assigned',
            'assigned_to' => $master2->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Волкова Елена',
            'phone' => '+7 900 666-77-88',
            'address' => 'ул. Пушкина, д. 25',
            'problem_text' => 'Починить кондиционер',
            'status' => 'done',
            'assigned_to' => $master2->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Новиков Дмитрий',
            'phone' => '+7 900 777-88-99',
            'address' => 'ул. Чехова, д. 30',
            'problem_text' => 'Отмена заявки',
            'status' => 'canceled',
        ]);
    }
}
