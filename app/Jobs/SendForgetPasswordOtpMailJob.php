<?php

namespace App\Jobs;

use App\Mail\SendForgetPasswordOtpMail;
use App\Models\ForgetPasswordOTP;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendForgetPasswordOtpMailJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  private $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // Create Random String of 6 digits
    $six_digit_random_number = random_int(100000, 999999);
    $ForgetPasswordUserExist = ForgetPasswordOTP::where('email', $this->user->email)->get();
    if (count($ForgetPasswordUserExist) > 0) {
      foreach ($ForgetPasswordUserExist as $existingOtp) {
        $existingOtp->delete();
      }
    }

    // Create DB Instance
    $ForgetPasswordUser = new ForgetPasswordOTP();
    $ForgetPasswordUser->email = $this->user->email;
    $ForgetPasswordUser->otp = $six_digit_random_number;
    $ForgetPasswordUser->save();

    // Send the email
    $mail = new SendForgetPasswordOtpMail($six_digit_random_number);
    Mail::to($this->user->email)->send($mail);
  }
}
