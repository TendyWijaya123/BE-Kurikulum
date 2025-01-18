<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class IeaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Deskripsi Sarjana Terapan
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 1', 'description' => 'Apply knowledge of mathematics, natural science, computing and engineering fundamentals and an engineering specialization as specified in SK1 to SK4 respectively to defined and applied engineering procedures, processes, systems or methodologies.'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 2', 'description' => 'Identify, formulate, research literature and analyze broadly-defined engineering problems reaching substantiated conclusions using analytical tools appropriate to the discipline or area of specialisation. (SK1 to SK4)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 3', 'description' => 'Design solutions for broadly- defined engineering technology problems and contribute to the design of systems, components or processes to meet identified needs with appropriate consideration for public health and safety, whole-life cost, net zero carbon as well as resource, cultural, societal, and environmental considerations as required (SK5)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 4', 'description' => 'Conduct investigations of broadly-defined engineering problems; locate, search and select relevant data from codes, data bases and literature, design and conduct experiments to provide valid conclusions (SK8)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 5', 'description' => 'Select and apply, and recognize limitations of appropriate techniques, resources, and modern engineering and IT tools, including prediction and modelling, to broadly-defined engineering problems (SK2 and SK6)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 6', 'description' => 'When solving broadly-defined engineering problems, analyze and evaluate sustainable development impacts* to: society, the economy, sustainability, health and safety, legal frameworks, and the environment (SK1, SK5, and SK7)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 7', 'description' => 'Understand and commit to professional ethics and norms of engineering technology practice including compliance with national and international laws. Demonstrate an understanding of the need for diversity and inclusion (SK9)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 8', 'description' => 'Function effectively as an individual, and as a member or leader in diverse and inclusive teams and in multi-disciplinary, face-to-face, remote and distributed settings (SK9)'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 9', 'description' => 'Communicate effectively and inclusively on broadly-defined engineering activities with the engineering community and with society at large, such as being able to comprehend and write effective reports and design documentation, make effective presentations, taking into account cultural, language, and learning differences.'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 10', 'description' => 'Apply knowledge and understanding of engineering management principles and apply these to one’s own work, as a member or leader in a team and to manage projects in multidisciplinary environments.'],
            ['jenjang' => 'Sarjana Terapan', 'code' => 'SA 11', 'description' => 'Recognize the need for, and have the ability for i) independent and life-long learning and ii) critical thinking in the face of new specialist technologies (SK8)'],

            // Deskripsi Diploma III
            ['jenjang' => 'Diploma III', 'code' => 'DA 1', 'description' => 'Apply knowledge of mathematics, natural science, engineering fundamentals and an engineering specialization as specified in DK1 to DK4 respectively to wide practical procedures and practices.'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 2', 'description' => 'Identify and analyze well-defined engineering problems reaching substantiated conclusions using codified methods of analysis specific to their field of activity. (DK1 to DK4)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 3', 'description' => 'Design solutions for well-defined technical problems and assist with the design of systems, components or processes to meet specified needs with appropriate consideration for public health and safety as well as cultural, societal, and environmental considerations as required (DK5)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 4', 'description' => 'Conduct investigations of well-defined problems; locate and search relevant codes and catalogues, conduct standard tests and measurements (DK8)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 5', 'description' => 'Apply appropriate techniques, resources, and modern computing, engineering, and IT tools to well-defined engineering problems, with an awareness of the limitations. (DK2 and DK6)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 6', 'description' => 'When solving well-defined engineering problems, evaluate sustainable development impacts* to: society, the economy, sustainability, health and safety, legal frameworks, and the environment (DK1, DK5, and DK7)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 7', 'description' => 'Understand and commit to professional ethics and norms of technician practice including compliance with relevant laws. Demonstrate an understanding of the need for diversity and inclusion (DK9)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 8', 'description' => 'Function effectively as an individual, and as a member or leader in diverse and inclusive teams and in multi-disciplinary, face-to-face, remote and distributed settings (DK9)'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 9', 'description' => 'Communicate effectively and inclusively on well-defined engineering activities with the engineering community and with society at large, by being able to comprehend the work of others, document their own work, and give and receive clear instructions'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 10', 'description' => 'Demonstrate awareness of engineering management principles as a member or leader in a technical team and to manage projects in multidisciplinary environments'],
            ['jenjang' => 'Diploma III', 'code' => 'DA 11', 'description' => 'Recognize the need for, and have the ability for independent updating in the face of specialized technical knowledge (DK8)'],
        ];

        DB::table('iea')->insert($data);
    }
}
