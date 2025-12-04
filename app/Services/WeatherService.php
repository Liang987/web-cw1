<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    /**
     * 获取当前天气
     * 返回格式：['type' => 'Rain', 'is_day' => true]
     */
    public function getCurrentWeather()
    {
        // 🟢 模拟数据模式
        // 为了演示效果，我们随机返回一种天气，这样你每次刷新页面都能看到背景变化
        // 实际项目中，你可以解开下面的 API 调用代码
        
        $types = ['Clear', 'Rain', 'Snow', 'Clouds'];
        
        return [
            'type' => $types[array_rand($types)], // 随机选一个：晴天、雨天、雪天、多云
            'is_day' => true, 
        ];

        /* // 真实 API 调用示例 (需要申请 OpenWeatherMap Key)
        $apiKey = env('OPENWEATHER_API_KEY');
        $city = 'London';
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric");
        
        if ($response->successful()) {
            return [
                'type' => $response->json()['weather'][0]['main'],
                'is_day' => true // 简化处理
            ];
        }
        return ['type' => 'Clouds', 'is_day' => true];
        */
    }
}