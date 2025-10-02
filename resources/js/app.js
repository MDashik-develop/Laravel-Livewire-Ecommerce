
// import Swiper from 'swiper';
// import { Navigation, Pagination } from 'swiper/modules';

// // Swiper-এর প্রয়োজনীয় CSS ইম্পোর্ট করুন
// import 'swiper/css';
// import 'swiper/css/navigation';
// import 'swiper/css/pagination';


import Swiper from 'swiper';
import * as SwiperModules from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Expose globally so Alpine can use
window.Swiper = Swiper;
window.SwiperModules = SwiperModules;
