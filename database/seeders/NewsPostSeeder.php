<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::where('role', Role::TeamLeader)->first();

        $posts = [
            [
                'slug' => '2024-02-23-dangerous-harnesses',
                'title' => 'Dangerous Harnesses',
                'date' => '2024-02-23T20:57:00',
                'body' => <<<'EOL'
Watch out! These harnesses by Xinda are doing the rounds again.

[![A post shared by The Reach Climbing Wall (@thereachclimbingwall)](https://www.instagram.com/p/C3nWVzAt4kF/media/?size=l)](https://www.instagram.com/p/C3nWVzAt4kF/)
EOL
            ],
            [
                'slug' => '2024-03-28-welcome',
                'title' => 'Climbing Team Website 1.0',
                'date' => '2024-03-28T11:58:00Z',
                'body' => <<<'EOL'
Welcome to the new Fox Coverts Climbing Team portal! I am hoping to make this a useful tool for organising the team and keeping on top of all the things that I have let slip over the past few years. **This is a work of progress.** I have tried to make it work on mobile screens too, but there's certainly room for improvement. Please let me know if you have any suggestions, and especially if you find something that doesn't work for you.

## Bookings

My first focus is on organising instructors for bookings. At the moment the portal will send individual emails for each booking when you are invited, when the booking is confirmed, and when any of the details change. Please let me know that these emails are working for you... Do they have the right information in them? Is it useful to get the update emails? Do you want a summary email to remind you of bookings this week?

Bookings will start out **tentative** until we have a Lead Instructor available and, hopefully, a team to support them - then they will then be **confirmed**.

You will receive invitations by email to each booking, please respond to these as soon as you can. The respond button in your email will not require you to login, and you should be able to add the booking straight into any calendar you use. If you respond "Maybe" then the booking will appear both on your [rota](/rota) and on your [invites](/invite), and your name will move from the "Invite" list to the "Maybe" list on the booking.

I do plan to add some automated reminder emails in the future - but until then invite/update emails are all you will get.

## Personal & Emergency Contact Details

I have used details from Compass to set up your accounts. I **do not** plan to update your account from the Scouts' records going forward, but if there is an easy export option in the new Scouts website then I may look at doing this occasionally, or otherwise asking you each year to confirm your details are still correct.

Your contact phone number and emergency contact details will be accessible to the **Lead Instructor** for each booking. You may choose not to provide your phone number or emergency contact details, but if you have an emergency then the Lead Instructor will need to contact the **Team Leader** or the **District Lead Volunteer** to find your emergency contact details from the Scouts website instead.

Team Members will be able to access the phone number and emergency contact for the Lead Instructor of a booking.

## Coming Soon

Next on my list of priorities is adding **Permit** information to the system, so that Lead Instructors can easily find out what their team are able to deliver. I am also considering tracking who has the shared office **Keys**, who has a **Necker**, and a way of ordering **Team Clothing**.
EOL
            ],
            [
                'slug' => '2024-04-06-permits-and-keys',
                'title' => 'Permits & Keys',
                'date' => '2024-04-06T20:54:00+01:00',
                'body' => <<<'EOL'
The bookings system is working well, over half of our members have activated their accounts and started responding to bookings. We have been busy adding a record of everyone's **qualifications and permits**, and adding a system to track who has the **office keys**.

## Qualifications and Permits

The team leader for a booking is now able to view the in-date qualifications held by anyone attending the booking; this will help them to deliver safe sessions. We will be adding a screen for people to see their own qualifications, although your definitive record will be the Scouts website. We do not plan to add any permit expiry notifications, as the county already has a robust notification system in place. Please let the Team Leader know when you get any new climbing permits or qualifications so that we can keep the records here up to date too.

## Office Keys

The team has a few keys to access the climbing equipment store - these are shown around the website by a small key symbol on the guest list. Please update the website or let the **Team Leader** know when you pass a key on to someone else so that we can keep track of the keys. If you are running a weekend booking you can also get a key from the warden if needed.

## Roll Call

Team leaders are now able to take a register of members turning up to bookings, even if you have previously said you cannot make it; this will allow them to see contact details and qualifications for the team present on the day. Please try to keep your attendance up to date if possible as this will help team leaders to plan for the booking.

## Next up

We have ordered some new climbing team **neckers** for anyone who has not got one, and will be adding a screen to track who has got a necker. The Team Leader and District Lead Volunteer are also looking at a process for ordering climbing team **clothing**, which will be managed through the website. There are plans to add useful documents, including the team's **risk assessments**, and a process for recording **near misses and incidents**. Please keep passing your bugs and feedback along.
EOL
            ],
        ];

        foreach ($posts as $data) {
            if (! NewsPost::where('slug', $data['slug'])->exists()) {
                $post = new NewsPost();
                $post->slug = $data['slug'];
                $post->title = $data['title'];
                $post->created_at = $post->updated_at = $data['date'];
                $post->body = $data['body'];
                $post->author()->associate($author);
                $post->save();
            }
        }
    }
}
