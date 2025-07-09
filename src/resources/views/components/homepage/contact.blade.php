@props(['post', 'posts'])

{{-- Ensure that the post is set --}}
@if (empty($post))
@php
$post = $posts->first();
@endphp
@endif

@if (!empty($post))
<section id="{{ $post->postCategory->slug }}">
  <div class="py-60px md:py-20 lg:py-30">
    <div class="container">
      <div class="mb-10 md:mb-50px xl:mb-60px text-center wow fadeInUp" data-wow-delay=".3s">
        <h2
          class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em text-seondary-color dark:text-white-color">
          {{ $post->title ?? 'Contact Us' }}
        </h2>
        <p class="text-primary-color-light dark:text-body-color max-w-540px mx-auto mt-4">
          {{ $post->excerpt ?? 'We are here to help. Please fill out the form below to get in touch with us.' }}
        </p>
      </div>

      <div class="max-w-4xl mx-auto">
        <form action="#" method="POST">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
              <input type="text" name="name" id="name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
              <input type="email" name="email" id="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="md:col-span-2">
              <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
              <textarea name="message" id="message" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
          </div>
          <div class="mt-6 text-center">
            <button type="submit"
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endif