<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createVarietyClassicCarRally();
        $this->createFourWdAdventureChallenge();
        $this->createMotorcycleCharityRide();
    }

    protected function createVarietyClassicCarRally(): void
    {
        $event = Event::create([
            'title' => 'Variety Classic Car Rally - Gold Coast to Brisbane',
            'description' => 'Annual classic car rally raising funds for children\'s charity. Participants drive vintage and classic cars through scenic routes while raising awareness and donations.',
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-17',
            'cover_image_path' => null,
        ]);

        $this->createClassicCarRallyDays($event);
        $this->createClassicCarRallyParticipants($event);
    }

    protected function createFourWdAdventureChallenge(): void
    {
        $event = Event::create([
            'title' => '4WD Adventure Challenge - Sunshine Coast Hinterland',
            'description' => 'Off-road challenge where teams navigate through tough terrain to raise funds for children\'s medical equipment. All vehicles must be properly equipped 4WDs.',
            'start_date' => '2024-06-08',
            'end_date' => '2024-06-10',
            'cover_image_path' => null,
        ]);

        $this->createFourWdChallengeDays($event);
        $this->createFourWdChallengeParticipants($event);
    }

    protected function createMotorcycleCharityRide(): void
    {
        $event = Event::create([
            'title' => 'Motorcycle Charity Ride - Brisbane to Toowoomba',
            'description' => 'Charity motorcycle ride supporting children with disabilities. Riders of all skill levels welcome to participate in this scenic ride through Queensland\'s countryside.',
            'start_date' => '2024-09-20',
            'end_date' => '2024-09-22',
            'cover_image_path' => null,
        ]);

        $this->createMotorcycleRideDays($event);
        $this->createMotorcycleRideParticipants($event);
    }

    protected function createClassicCarRallyDays(Event $event): void
    {
        // Day 1: Registration & Welcome Dinner
        $day1 = $event->days()->create([
            'title' => 'Registration & Welcome Dinner',
            'date' => '2024-03-15',
            'subtitle' => 'Kick-off event at Gold Coast Convention Centre',
            'image_path' => null,
            'sort_order' => 1,
            'itinerary_title' => 'Welcome and Preparation',
            'itinerary_description' => '<p>Morning vehicle inspections and participant registration. Afternoon welcome dinner with guest speakers from Variety Children\'s Charity.</p>',
        ]);

        // Add locations for Day 1
        $day1->locations()->createMany([
            [
                'name' => 'Gold Coast Convention Centre',
                'link_title' => 'View on Google Maps',
                'link_url' => 'https://maps.app.goo.gl/abc123',
                'sort_order' => 1,
            ],
            [
                'name' => 'Vehicle Inspection Area',
                'link_title' => 'Parking Location',
                'link_url' => 'https://maps.app.goo.gl/def456',
                'sort_order' => 2,
            ],
        ]);

        // Add resources for Day 1
        $day1->resources()->createMany([
            [
                'title' => 'Event Program',
                'url' => 'https://variety.org.au/events/classic-car-rally-program.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Safety Guidelines',
                'url' => 'https://variety.org.au/events/safety-guidelines.pdf',
                'sort_order' => 2,
            ],
        ]);

        // Day 2: Scenic Drive to Tamborine Mountain
        $day2 = $event->days()->create([
            'title' => 'Scenic Drive to Tamborine Mountain',
            'date' => '2024-03-16',
            'subtitle' => 'Mountain roads and rainforest views',
            'image_path' => null,
            'sort_order' => 2,
            'itinerary_title' => 'Day 2 Itinerary',
            'itinerary_description' => '<p>Morning departure from Gold Coast. Scenic drive through hinterland with stops at lookout points. Lunch at mountain winery. Afternoon activities at Tamborine Mountain.</p>',
        ]);

        // Add locations for Day 2
        $day2->locations()->createMany([
            [
                'name' => 'Springbrook Lookout',
                'link_title' => 'View on Google Maps',
                'link_url' => 'https://maps.app.goo.gl/ghi789',
                'sort_order' => 1,
            ],
            [
                'name' => 'Tamborine Mountain Winery',
                'link_title' => 'Winery Website',
                'link_url' => 'https://tmbwinery.com.au',
                'sort_order' => 2,
            ],
            [
                'name' => 'Gallery Walk',
                'link_title' => 'Shopping Precinct',
                'link_url' => 'https://maps.app.goo.gl/jkl012',
                'sort_order' => 3,
            ],
        ]);

        // Add resources for Day 2
        $day2->resources()->createMany([
            [
                'title' => 'Scenic Route Map',
                'url' => 'https://variety.org.au/events/scenic-route-map.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Tamborine Mountain Guide',
                'url' => 'https://visitTamborine.com.au/guide',
                'sort_order' => 2,
            ],
        ]);

        // Day 3: Finale Drive to Brisbane
        $day3 = $event->days()->create([
            'title' => 'Finale Drive to Brisbane',
            'date' => '2024-03-17',
            'subtitle' => 'Grand arrival and charity auction',
            'image_path' => null,
            'sort_order' => 3,
            'itinerary_title' => 'Grand Finale',
            'itinerary_description' => '<p>Morning drive from Tamborine to Brisbane. Grand arrival at South Bank Parklands. Charity auction and awards ceremony in the evening.</p>',
        ]);

        // Add locations for Day 3
        $day3->locations()->createMany([
            [
                'name' => 'South Bank Parklands',
                'link_title' => 'Arrival Location',
                'link_url' => 'https://maps.app.goo.gl/mno345',
                'sort_order' => 1,
            ],
            [
                'name' => 'Brisbane Convention Centre',
                'link_title' => 'Auction Venue',
                'link_url' => 'https://maps.app.goo.gl/pqr678',
                'sort_order' => 2,
            ],
        ]);

        // Add resources for Day 3
        $day3->resources()->createMany([
            [
                'title' => 'Auction Catalogue',
                'url' => 'https://variety.org.au/events/auction-catalogue.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Parking Information',
                'url' => 'https://variety.org.au/events/parking-info.pdf',
                'sort_order' => 2,
            ],
        ]);
    }

    protected function createFourWdChallengeDays(Event $event): void
    {
        // Day 1: Safety Briefing & Skills Challenge
        $day1 = $event->days()->create([
            'title' => 'Safety Briefing & Skills Challenge',
            'date' => '2024-06-08',
            'subtitle' => 'Preparation day at Sunshine Coast 4WD Park',
            'image_path' => null,
            'sort_order' => 1,
            'itinerary_title' => 'Preparation Day',
            'itinerary_description' => '<p>Morning vehicle safety checks and equipment verification. Driver briefing on trail rules and safety. Afternoon skills challenge course to test team capabilities.</p>',
        ]);

        // Add locations for Day 1
        $day1->locations()->createMany([
            [
                'name' => 'Sunshine Coast 4WD Park',
                'link_title' => 'Park Entrance',
                'link_url' => 'https://maps.app.goo.gl/stu901',
                'sort_order' => 1,
            ],
            [
                'name' => 'Skills Challenge Course',
                'link_title' => 'Course Location',
                'link_url' => 'https://maps.app.goo.gl/vwx234',
                'sort_order' => 2,
            ],
        ]);

        // Add resources for Day 1
        $day1->resources()->createMany([
            [
                'title' => 'Safety Checklist',
                'url' => 'https://variety.org.au/events/4wd-safety-checklist.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Equipment Requirements',
                'url' => 'https://variety.org.au/events/4wd-equipment-list.pdf',
                'sort_order' => 2,
            ],
        ]);

        // Day 2: Hinterland Trail Challenge
        $day2 = $event->days()->create([
            'title' => 'Hinterland Trail Challenge',
            'date' => '2024-06-09',
            'subtitle' => 'Full day off-road adventure',
            'image_path' => null,
            'sort_order' => 2,
            'itinerary_title' => 'Main Challenge Day',
            'itinerary_description' => '<p>Morning departure for hinterland trails. Challenging off-road navigation through various terrain types. Lunch at scenic lookout point. Afternoon obstacle course and team challenges.</p>',
        ]);

        // Add locations for Day 2
        $day2->locations()->createMany([
            [
                'name' => 'Hinterland Trail Start',
                'link_title' => 'Trailhead Location',
                'link_url' => 'https://maps.app.goo.gl/yz1234',
                'sort_order' => 1,
            ],
            [
                'name' => 'Mountain View Lookout',
                'link_title' => 'Lunch Stop',
                'link_url' => 'https://maps.app.goo.gl/ab5678',
                'sort_order' => 2,
            ],
            [
                'name' => 'Obstacle Course Area',
                'link_title' => 'Challenge Location',
                'link_url' => 'https://maps.app.goo.gl/cd9012',
                'sort_order' => 3,
            ],
        ]);

        // Add resources for Day 2
        $day2->resources()->createMany([
            [
                'title' => 'Trail Map',
                'url' => 'https://variety.org.au/events/hinterland-trail-map.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Navigation Guide',
                'url' => 'https://variety.org.au/events/offroad-navigation-guide.pdf',
                'sort_order' => 2,
            ],
        ]);

        // Day 3: Team Challenge & Awards
        $day3 = $event->days()->create([
            'title' => 'Team Challenge & Awards',
            'date' => '2024-06-10',
            'subtitle' => 'Final challenges and celebration',
            'image_path' => null,
            'sort_order' => 3,
            'itinerary_title' => 'Finale Day',
            'itinerary_description' => '<p>Morning team navigation challenge. Charity presentation and fundraising update. Awards ceremony and celebration lunch. Afternoon departure.</p>',
        ]);

        // Add locations for Day 3
        $day3->locations()->createMany([
            [
                'name' => 'Navigation Challenge Start',
                'link_title' => 'Challenge Location',
                'link_url' => 'https://maps.app.goo.gl/ef3456',
                'sort_order' => 1,
            ],
            [
                'name' => 'Awards Pavilion',
                'link_title' => 'Ceremony Location',
                'link_url' => 'https://maps.app.goo.gl/gh7890',
                'sort_order' => 2,
            ],
        ]);

        // Add resources for Day 3
        $day3->resources()->createMany([
            [
                'title' => 'Event Results',
                'url' => 'https://variety.org.au/events/4wd-challenge-results.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Photo Gallery',
                'url' => 'https://variety.org.au/events/4wd-challenge-photos',
                'sort_order' => 2,
            ],
        ]);
    }

    protected function createMotorcycleRideDays(Event $event): void
    {
        // Day 1: Brisbane to Ipswich Ride
        $day1 = $event->days()->create([
            'title' => 'Brisbane to Ipswich Ride',
            'date' => '2024-09-20',
            'subtitle' => 'First leg of the journey',
            'image_path' => null,
            'sort_order' => 1,
            'itinerary_title' => 'Day 1 Itinerary',
            'itinerary_description' => '<p>Morning registration and safety briefing at Brisbane starting point. Group ride to Ipswich with scheduled rest stops. Lunch at historic Ipswich location. Afternoon arrival and check-in.</p>',
        ]);

        // Add locations for Day 1
        $day1->locations()->createMany([
            [
                'name' => 'Brisbane Starting Point',
                'link_title' => 'Registration Location',
                'link_url' => 'https://maps.app.goo.gl/ij1234',
                'sort_order' => 1,
            ],
            [
                'name' => 'Rest Stop 1 - Redbank Plaza',
                'link_title' => 'Morning Break',
                'link_url' => 'https://maps.app.goo.gl/kl5678',
                'sort_order' => 2,
            ],
            [
                'name' => 'Ipswich Historic Precinct',
                'link_title' => 'Lunch Location',
                'link_url' => 'https://maps.app.goo.gl/mn9012',
                'sort_order' => 3,
            ],
        ]);

        // Add resources for Day 1
        $day1->resources()->createMany([
            [
                'title' => 'Route Map - Day 1',
                'url' => 'https://variety.org.au/events/motorcycle-ride-day1-map.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Safety Briefing Slides',
                'url' => 'https://variety.org.au/events/motorcycle-safety-slides.pdf',
                'sort_order' => 2,
            ],
        ]);

        // Day 2: Ipswich to Toowoomba Ride
        $day2 = $event->days()->create([
            'title' => 'Ipswich to Toowoomba Ride',
            'date' => '2024-09-21',
            'subtitle' => 'Scenic mountain ride',
            'image_path' => null,
            'sort_order' => 2,
            'itinerary_title' => 'Day 2 Itinerary',
            'itinerary_description' => '<p>Morning departure from Ipswich. Scenic ride through Lockyer Valley with photo opportunities. Lunch stop at Gatton. Afternoon ride up the range to Toowoomba with spectacular views.</p>',
        ]);

        // Add locations for Day 2
        $day2->locations()->createMany([
            [
                'name' => 'Lockyer Valley Viewpoint',
                'link_title' => 'Photo Opportunity',
                'link_url' => 'https://maps.app.goo.gl/op3456',
                'sort_order' => 1,
            ],
            [
                'name' => 'Gatton Caf├ę',
                'link_title' => 'Lunch Stop',
                'link_url' => 'https://maps.app.goo.gl/qr7890',
                'sort_order' => 2,
            ],
            [
                'name' => 'Toowoomba Range Lookout',
                'link_title' => 'Scenic View',
                'link_url' => 'https://maps.app.goo.gl/st1234',
                'sort_order' => 3,
            ],
        ]);

        // Add resources for Day 2
        $day2->resources()->createMany([
            [
                'title' => 'Route Map - Day 2',
                'url' => 'https://variety.org.au/events/motorcycle-ride-day2-map.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Lockyer Valley Guide',
                'url' => 'https://visitlockyer.com.au/guide',
                'sort_order' => 2,
            ],
        ]);

        // Day 3: Toowoomba Charity Event
        $day3 = $event->days()->create([
            'title' => 'Toowoomba Charity Event',
            'date' => '2024-09-22',
            'subtitle' => 'Final celebrations and fundraising',
            'image_path' => null,
            'sort_order' => 3,
            'itinerary_title' => 'Finale Day',
            'itinerary_description' => '<p>Morning scenic ride around Toowoomba gardens. Charity lunch event with guest speakers. Awards presentation and fundraising total announcement. Afternoon return transport arrangements.</p>',
        ]);

        // Add locations for Day 3
        $day3->locations()->createMany([
            [
                'name' => 'Toowoomba Botanic Gardens',
                'link_title' => 'Morning Ride Location',
                'link_url' => 'https://maps.app.goo.gl/uv5678',
                'sort_order' => 1,
            ],
            [
                'name' => 'Toowoomba Convention Centre',
                'link_title' => 'Charity Lunch Venue',
                'link_url' => 'https://maps.app.goo.gl/wx9012',
                'sort_order' => 2,
            ],
        ]);

        // Add resources for Day 3
        $day3->resources()->createMany([
            [
                'title' => 'Event Program',
                'url' => 'https://variety.org.au/events/motorcycle-ride-program.pdf',
                'sort_order' => 1,
            ],
            [
                'title' => 'Fundraising Update',
                'url' => 'https://variety.org.au/events/motorcycle-fundraising-update',
                'sort_order' => 2,
            ],
        ]);
    }

    protected function createClassicCarRallyParticipants(Event $event): void
    {
        // Vehicle 1: 1967 Ford Mustang GT (MUST67GT)
        $event->participants()->createMany([
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '0412345678',
                'vehicle' => 'MUST67GT',
                'status' => 'active',
                'emergency_contact_name' => 'Jane Smith',
                'emergency_contact_phone' => '0412345679',
                'emergency_contact_relationship' => 'Spouse',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Smith',
                'email' => 'sarah.smith@example.com',
                'phone' => '0412345680',
                'vehicle' => 'MUST67GT',
                'status' => 'active',
                'emergency_contact_name' => 'John Smith',
                'emergency_contact_phone' => '0412345678',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);

        // Vehicle 2: 1956 Chevrolet Bel Air (CHEV56BA)
        $event->participants()->createMany([
            [
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael.j@example.com',
                'phone' => '0423456789',
                'vehicle' => 'CHEV56BA',
                'status' => 'active',
                'emergency_contact_name' => 'Emily Johnson',
                'emergency_contact_phone' => '0423456790',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Johnson',
                'email' => 'emily.j@example.com',
                'phone' => '0423456790',
                'vehicle' => 'CHEV56BA',
                'status' => 'active',
                'emergency_contact_name' => 'Michael Johnson',
                'emergency_contact_phone' => '0423456789',
                'emergency_contact_relationship' => 'Husband',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'email' => 'david.j@example.com',
                'phone' => '0423456791',
                'vehicle' => 'CHEV56BA',
                'status' => 'active',
                'emergency_contact_name' => 'Michael Johnson',
                'emergency_contact_phone' => '0423456789',
                'emergency_contact_relationship' => 'Brother',
            ],
        ]);

        // Vehicle 3: 1970 Dodge Challenger (DODG70CH)
        $event->participants()->createMany([
            [
                'first_name' => 'Robert',
                'last_name' => 'Williams',
                'email' => 'robert.w@example.com',
                'phone' => '0434567890',
                'vehicle' => 'DODG70CH',
                'status' => 'active',
                'emergency_contact_name' => 'Lisa Williams',
                'emergency_contact_phone' => '0434567891',
                'emergency_contact_relationship' => 'Wife',
            ],
        ]);

        // Vehicle 4: 1965 Volkswagen Beetle (VW65BTL)
        $event->participants()->createMany([
            [
                'first_name' => 'Emma',
                'last_name' => 'Brown',
                'email' => 'emma.b@example.com',
                'phone' => '0445678901',
                'vehicle' => 'VW65BTL',
                'status' => 'active',
                'emergency_contact_name' => 'David Brown',
                'emergency_contact_phone' => '0445678902',
                'emergency_contact_relationship' => 'Partner',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Brown',
                'email' => 'sophia.b@example.com',
                'phone' => '0445678903',
                'vehicle' => 'VW65BTL',
                'status' => 'active',
                'emergency_contact_name' => 'Emma Brown',
                'emergency_contact_phone' => '0445678901',
                'emergency_contact_relationship' => 'Sister',
            ],
        ]);

        // Vehicle 5: 1969 Pontiac GTO (PONT69GT)
        $event->participants()->createMany([
            [
                'first_name' => 'Michael',
                'last_name' => 'Davis',
                'email' => 'michael.d@example.com',
                'phone' => '0456789012',
                'vehicle' => 'PONT69GT',
                'status' => 'active',
                'emergency_contact_name' => 'Sophia Davis',
                'emergency_contact_phone' => '0456789013',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Davis',
                'email' => 'sophia.d@example.com',
                'phone' => '0456789013',
                'vehicle' => 'PONT69GT',
                'status' => 'active',
                'emergency_contact_name' => 'Michael Davis',
                'emergency_contact_phone' => '0456789012',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);
    }

    protected function createFourWdChallengeParticipants(Event $event): void
    {
        // Vehicle 1: 2020 Toyota LandCruiser 79 Series (TOYL2079)
        $event->participants()->createMany([
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'james.w@example.com',
                'phone' => '0467890123',
                'vehicle' => 'TOYL2079',
                'status' => 'active',
                'emergency_contact_name' => 'Emma Wilson',
                'emergency_contact_phone' => '0467890124',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Wilson',
                'email' => 'emma.w@example.com',
                'phone' => '0467890124',
                'vehicle' => 'TOYL2079',
                'status' => 'active',
                'emergency_contact_name' => 'James Wilson',
                'emergency_contact_phone' => '0467890123',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);

        // Vehicle 2: 2018 Jeep Wrangler Rubicon (JEEP18WR)
        $event->participants()->createMany([
            [
                'first_name' => 'Olivia',
                'last_name' => 'Taylor',
                'email' => 'olivia.t@example.com',
                'phone' => '0478901234',
                'vehicle' => 'JEEP18WR',
                'status' => 'active',
                'emergency_contact_name' => 'Liam Taylor',
                'emergency_contact_phone' => '0478901235',
                'emergency_contact_relationship' => 'Husband',
            ],
            [
                'first_name' => 'Liam',
                'last_name' => 'Taylor',
                'email' => 'liam.t@example.com',
                'phone' => '0478901235',
                'vehicle' => 'JEEP18WR',
                'status' => 'active',
                'emergency_contact_name' => 'Olivia Taylor',
                'emergency_contact_phone' => '0478901234',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Noah',
                'last_name' => 'Taylor',
                'email' => 'noah.t@example.com',
                'phone' => '0478901236',
                'vehicle' => 'JEEP18WR',
                'status' => 'active',
                'emergency_contact_name' => 'Olivia Taylor',
                'emergency_contact_phone' => '0478901234',
                'emergency_contact_relationship' => 'Son',
            ],
        ]);

        // Vehicle 3: 2019 Nissan Patrol TI (NISS19PT)
        $event->participants()->createMany([
            [
                'first_name' => 'William',
                'last_name' => 'Anderson',
                'email' => 'william.a@example.com',
                'phone' => '0489012345',
                'vehicle' => 'NISS19PT',
                'status' => 'active',
                'emergency_contact_name' => 'Charlotte Anderson',
                'emergency_contact_phone' => '0489012346',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Charlotte',
                'last_name' => 'Anderson',
                'email' => 'charlotte.a@example.com',
                'phone' => '0489012346',
                'vehicle' => 'NISS19PT',
                'status' => 'active',
                'emergency_contact_name' => 'William Anderson',
                'emergency_contact_phone' => '0489012345',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);

        // Vehicle 4: 2021 Ford Ranger Raptor (FORD21RR)
        $event->participants()->createMany([
            [
                'first_name' => 'Sophia',
                'last_name' => 'Martinez',
                'email' => 'sophia.m@example.com',
                'phone' => '0490123456',
                'vehicle' => 'FORD21RR',
                'status' => 'active',
                'emergency_contact_name' => 'Daniel Martinez',
                'emergency_contact_phone' => '0490123457',
                'emergency_contact_relationship' => 'Husband',
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Martinez',
                'email' => 'daniel.m@example.com',
                'phone' => '0490123457',
                'vehicle' => 'FORD21RR',
                'status' => 'active',
                'emergency_contact_name' => 'Sophia Martinez',
                'emergency_contact_phone' => '0490123456',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Lucas',
                'last_name' => 'Martinez',
                'email' => 'lucas.m@example.com',
                'phone' => '0490123458',
                'vehicle' => 'FORD21RR',
                'status' => 'active',
                'emergency_contact_name' => 'Sophia Martinez',
                'emergency_contact_phone' => '0490123456',
                'emergency_contact_relationship' => 'Son',
            ],
        ]);

        // Vehicle 5: 2017 Land Rover Defender (LAND17DF)
        $event->participants()->createMany([
            [
                'first_name' => 'Benjamin',
                'last_name' => 'Thomas',
                'email' => 'benjamin.t@example.com',
                'phone' => '0401234567',
                'vehicle' => 'LAND17DF',
                'status' => 'active',
                'emergency_contact_name' => 'Amelia Thomas',
                'emergency_contact_phone' => '0401234568',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Amelia',
                'last_name' => 'Thomas',
                'email' => 'amelia.t@example.com',
                'phone' => '0401234568',
                'vehicle' => 'LAND17DF',
                'status' => 'active',
                'emergency_contact_name' => 'Benjamin Thomas',
                'emergency_contact_phone' => '0401234567',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);
    }

    protected function createMotorcycleRideParticipants(Event $event): void
    {
        // Vehicle 1: 2022 Harley-Davidson Road King (HARL22RK)
        $event->participants()->createMany([
            [
                'first_name' => 'Ethan',
                'last_name' => 'Jackson',
                'email' => 'ethan.j@example.com',
                'phone' => '0411234567',
                'vehicle' => 'HARL22RK',
                'status' => 'active',
                'emergency_contact_name' => 'Ava Jackson',
                'emergency_contact_phone' => '0411234568',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Ava',
                'last_name' => 'Jackson',
                'email' => 'ava.j@example.com',
                'phone' => '0411234568',
                'vehicle' => 'HARL22RK',
                'status' => 'active',
                'emergency_contact_name' => 'Ethan Jackson',
                'emergency_contact_phone' => '0411234567',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);

        // Vehicle 2: 2021 BMW R 1250 GS (BMW21GS)
        $event->participants()->createMany([
            [
                'first_name' => 'Mia',
                'last_name' => 'White',
                'email' => 'mia.w@example.com',
                'phone' => '0422345678',
                'vehicle' => 'BMW21GS',
                'status' => 'active',
                'emergency_contact_name' => 'Noah White',
                'emergency_contact_phone' => '0422345679',
                'emergency_contact_relationship' => 'Husband',
            ],
            [
                'first_name' => 'Noah',
                'last_name' => 'White',
                'email' => 'noah.w@example.com',
                'phone' => '0422345679',
                'vehicle' => 'BMW21GS',
                'status' => 'active',
                'emergency_contact_name' => 'Mia White',
                'emergency_contact_phone' => '0422345678',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Oliver',
                'last_name' => 'White',
                'email' => 'oliver.w@example.com',
                'phone' => '0422345680',
                'vehicle' => 'BMW21GS',
                'status' => 'active',
                'emergency_contact_name' => 'Mia White',
                'emergency_contact_phone' => '0422345678',
                'emergency_contact_relationship' => 'Son',
            ],
        ]);

        // Vehicle 3: 2020 Honda Africa Twin (HOND20AT)
        $event->participants()->createMany([
            [
                'first_name' => 'Alexander',
                'last_name' => 'Harris',
                'email' => 'alexander.h@example.com',
                'phone' => '0433456789',
                'vehicle' => 'HOND20AT',
                'status' => 'active',
                'emergency_contact_name' => 'Grace Harris',
                'emergency_contact_phone' => '0433456790',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Harris',
                'email' => 'grace.h@example.com',
                'phone' => '0433456790',
                'vehicle' => 'HOND20AT',
                'status' => 'active',
                'emergency_contact_name' => 'Alexander Harris',
                'emergency_contact_phone' => '0433456789',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);

        // Vehicle 4: 2019 Triumph Bonneville T120 (TRIU19BT)
        $event->participants()->createMany([
            [
                'first_name' => 'Charlotte',
                'last_name' => 'Clark',
                'email' => 'charlotte.c@example.com',
                'phone' => '0444567890',
                'vehicle' => 'TRIU19BT',
                'status' => 'active',
                'emergency_contact_name' => 'Henry Clark',
                'emergency_contact_phone' => '0444567891',
                'emergency_contact_relationship' => 'Husband',
            ],
            [
                'first_name' => 'Henry',
                'last_name' => 'Clark',
                'email' => 'henry.c@example.com',
                'phone' => '0444567891',
                'vehicle' => 'TRIU19BT',
                'status' => 'active',
                'emergency_contact_name' => 'Charlotte Clark',
                'emergency_contact_phone' => '0444567890',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Lily',
                'last_name' => 'Clark',
                'email' => 'lily.c@example.com',
                'phone' => '0444567892',
                'vehicle' => 'TRIU19BT',
                'status' => 'active',
                'emergency_contact_name' => 'Charlotte Clark',
                'emergency_contact_phone' => '0444567890',
                'emergency_contact_relationship' => 'Daughter',
            ],
        ]);

        // Vehicle 5: 2023 Kawasaki Versys 1000 (KAWA23V1)
        $event->participants()->createMany([
            [
                'first_name' => 'Daniel',
                'last_name' => 'Lewis',
                'email' => 'daniel.l@example.com',
                'phone' => '0455678901',
                'vehicle' => 'KAWA23V1',
                'status' => 'active',
                'emergency_contact_name' => 'Emma Lewis',
                'emergency_contact_phone' => '0455678902',
                'emergency_contact_relationship' => 'Wife',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Lewis',
                'email' => 'emma.l@example.com',
                'phone' => '0455678902',
                'vehicle' => 'KAWA23V1',
                'status' => 'active',
                'emergency_contact_name' => 'Daniel Lewis',
                'emergency_contact_phone' => '0455678901',
                'emergency_contact_relationship' => 'Husband',
            ],
        ]);
    }
}
