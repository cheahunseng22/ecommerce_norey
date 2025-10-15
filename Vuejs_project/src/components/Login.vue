<template>
  <div class="min-h-screen bg-[#F0F5F6] flex items-center justify-center p-4 font-sans">
    <!-- Main container for the login card -->
    <div class="relative w-full max-w-4xl h-[600px] bg-[#F7F5EE] rounded-3xl overflow-hidden shadow-xl border border-[#3E6B65]">
      
      <!-- Decorative circles in the top-left -->
      <div class="absolute top-[-20px] left-[-20px] w-20 h-20 bg-[#FD7E27] rounded-full z-10"></div>
      <div class="absolute top-[40px] left-[50px] w-12 h-12 bg-[#FD7E27] rounded-full z-10"></div>

      <div class="flex h-full">
        <!-- Left Panel: Illustration -->
        <div class="w-1/2 flex items-center justify-center p-8 relative">
          <!--
            The illustration from the image is an SVG asset.
            To display it, ensure you have an 'engineer-illustration.svg' file
            in your 'public/images/' directory.

            Here's the content for 'public/images/engineer-illustration.svg'
            (This SVG was provided in your previous request's output):

            <svg width="300" height="250" viewBox="0 0 300 250" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M150 100L150 20L100 50L50 80L30 150L60 180L150 200L240 180L270 150L250 80L200 50L150 20Z" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 20V0" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 20C160 15 170 15 180 20" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 100L150 110" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 110C160 115 170 115 180 110" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 100L140 110L130 100L120 110L110 100" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M100 100L90 120L80 150L70 170L60 180" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M200 100L210 120L220 150L230 170L240 180" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M60 180L150 200L240 180" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 200V240" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M120 180L100 220" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M180 180L200 220" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M130 140L140 150L150 140L160 150L170 140" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M150 120L150 130" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

            <!-- Kiosk -->
            <rect x="70" y="90" width="80" height="130" rx="10" stroke="#FD7E27" stroke-width="3"/>
            <rect x="80" y="100" width="60" height="100" rx="5" stroke="#3E6B65" stroke-width="2"/>
            <circle cx="110" cy="150" r="8" stroke="#3E6B65" stroke-width="1.5" fill="none"/>
            <path d="M110 142L110 158" stroke="#3E6B65" stroke-width="1" stroke-linecap="round"/>
            <path d="M102 150L118 150" stroke="#3E6B65" stroke-width="1" stroke-linecap="round"/>
            <path d="M110 220L110 240" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M100 220L120 220" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M105 220L105 230" stroke="#3E6B65" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M115 220L115 230" stroke="#3E6B65" stroke-width="1.5" stroke-linecap="round"/>

            <!-- Engineer figure -->
            <path d="M165 105L175 140L190 150L200 140L210 100L185 80L165 105Z" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M185 80C190 70 200 70 205 80" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="185" cy="70" r="5" fill="#3E6B65"/>
            <path d="M185 75L185 85" stroke="#3E6B65" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M180 80L190 80" stroke="#3E6B65" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M185 70L190 60L200 65L205 75" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M175 140L180 160L190 165L200 155L205 140" stroke="#FD7E27" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M170 150L175 170L185 175L195 165L200 150" stroke="#6C9A93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M185 175V190" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M180 185L190 185" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M180 190L180 200" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M190 190L190 200" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M165 105L160 115L155 120" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
            <path d="M205 140L210 130L215 125" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>

            <!-- Engineer cap -->
            <path d="M175 55L185 40L200 45L210 55" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M185 40C190 35 195 35 200 40" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M175 55L170 60L165 65" stroke="#3E6B65" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M205 140L210 130L215 125" stroke="#3E6B65" stroke-width="2" stroke-linecap="round"/>
           
          
          <img
            src="/public/images/clothes_remove.png"
            alt="Engineer illustration"
            class="w-full h-auto max-w-[300px] object-contain"
          />
        </div>

        <!-- Right Panel: Login Form -->
        <div class="w-1/2 bg-[#6b1f2e] p-10 flex flex-col justify-center text-white">
          <h2 class="text-4xl font-bold mb-4">Login</h2>
          <p class="text-black-500 font-medium"> To clothes Management website </p>

        <form @submit.prevent="login" class="space-y-6">
          <div>
            <label class="block text-black-700 font-semibold mb-2 ml-1">Email</label>
            <div class="relative">
              <input v-model="email" type="email" placeholder="clothes@fashion.com" class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-400
                            focus:ring-2 focus:ring-purple-100 outline-none transition-all
                            placeholder-gray-400 text-black"> <!-- Added text-black -->
              <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            
          </div>

          <div>
            <label class="block text-black-700 font-semibold mb-2 ml-1">Password</label>
            <div class="relative">
              <input v-model="password" type="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-400
                            focus:ring-2 focus:ring-purple-100 outline-none transition-all
                            placeholder-gray-400 text-black"> <!-- Added text-black for consistency -->
              <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>

          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input type="checkbox" class="rounded text-white-500 focus:ring-purple-400">
              <span class="text-black-600">Remember me</span>
            </label>
       
          </div>

          <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white py-3.5 rounded-xl
                         font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all
                         active:scale-95 focus:outline-none focus:ring-2 focus:ring-purple-400">
            Sign In
            <i class="fas fa-arrow-right ml-2"></i>
          </button>

          <p class="text-center text-black-500 mt-6">
            New here?
            <a href="#" class="text-[#000000] font-medium hover:text-purple-700">Create account</a>
          </p>
        </form>

          <!-- Forgot password link -->
          <a href="#" class="text-[#efebeb] text-center mt-4 text-sm hover:underline">
            Forgot password?
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../axios/Axios';

const email = ref('');
const password = ref('');
const router = useRouter();

const login = async () => {
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value,
    }, { withCredentials: true });

    const { token, role, name, id } = response.data;

    const sessionId = sessionStorage.getItem('session_id') || crypto.randomUUID();
    // sessionStorage.setItem('session_id', sessionId);
    if (token) {
      sessionStorage.setItem('auth_token', token);
      sessionStorage.setItem('name', name);
      sessionStorage.setItem('role', role);
      sessionStorage.setItem('id', id);

      const redirectPath = role === 'admin' ? '/admin/home' :
        role === 'seller' ? '/user' : '/home';
      router.push(redirectPath);
    } else {
      alert('Authentication failed: No token received');
    }
  } catch (error) {
    console.error('Login error:', error);
    alert(error.response?.data?.message || 'Login failed. Please check your credentials.');
  }
};
</script>

<style scoped>
/*
For the custom checkbox to work as intended with `appearance-none`:
- The `checked:bg-[#FD7E27]` applies the orange background when checked.
- If you want a custom checkmark icon inside the checkbox, you would typically use a background image on `input[type="checkbox"]:checked`
  or a pseudo-element (`::before`/`::after`) which is more complex with Tailwind's utility-first approach.
  For simplicity, `checked:bg` provides the primary visual cue from the image.
*/

/*
Removed custom styles like @keyframes bounce-slow, .animate-bounce-slow,
input:focus+i, and input::-webkit-input-placeholder as they are not
present in the target design.
*/
</style>