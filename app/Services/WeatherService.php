<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    /**
     * Get current weather data.
     * 获取当前天气数据。
     *
     * @return array Returns an array containing weather type and day/night status. / 返回包含天气类型和白天/夜晚状态的数组。
     */
    public function getCurrentWeather()
    {
        // Mock Data Mode (for demonstration)
        // 模拟数据模式 (用于演示)
        // To demonstrate the dynamic background effect, we return a random weather type.
        // 为了演示动态背景效果，我们随机返回一种天气类型。
        // This ensures we see changes every time when we refresh the page.
        // 这样你每次刷新页面都能看到变化。
        
        $types = ['Clear', 'Rain', 'Snow', 'Clouds'];
        
        return [
            'type' => $types[array_rand($types)], // Randomly select one: Clear, Rain, Snow, or Clouds / 随机选一个：晴天、雨天、雪天、多云
            'is_day' => true, 
        ];

        /* // Real API Call Example
           // 真实 API 调用示例
        
        // Get API key from environment variables
        // 从环境变量获取 API 密钥
        $apiKey = env('OPENWEATHER_API_KEY');
        $city = 'Swansea';

        // Make a GET request to the OpenWeatherMap API
        // 向 OpenWeatherMap API 发送 GET 请求
        $response = Http::get("https://api.openweathermap.org/xxxxxx");
        
        // Check if the request was successful
        // 检查请求是否成功
        if ($response->successful()) {
            return [
                'type' => $response->json()['weather'][0]['main'], // e.g., 'Rain', 'Clear'
                'is_day' => true // Simplified for this example / 此示例中简化处理
            ];
        }
        
        // Fallback data in case of API failure
        // API 调用失败时的回退数据
        return ['type' => 'Clouds', 'is_day' => true];
        */
    }
}