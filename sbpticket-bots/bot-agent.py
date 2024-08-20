import telebot
import requests
import json
from telebot import types
import smtp

bot = telebot.TeleBot('7474122703:AAHTPofV9xLIY9vdgi3cS9b8dO-xrdNDw9w', parse_mode=None)

response = requests.get('http://localhost/sbpticket.com/get-registr-items.php')
buses = json.loads(response.text)[1]

@bot.message_handler(commands=['start'])
def start_message(message):
    markup = types.InlineKeyboardMarkup()
    
    button1 = types.InlineKeyboardButton('Список перевозчиков', callback_data='get_carriers')
    button2 = types.InlineKeyboardButton('Добавить переввозчика', callback_data='create_carriers')
    markup.add(button1)
    markup.add(button2)

    bot.send_message(message.chat.id, "Привет, {0.first_name}! \n\n📅 За день заработано - 15 000 руб. \n\n📅 За неделю заработано - 215 000 руб.\n\n📆 За месяц заработано - 1 200 540 руб.".format(message.from_user), reply_markup=markup)

html_template = """
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приглашение</title>
</head>
<body>
    <table cellpadding="0" cellspacing="0" style="border: 0; margin: 0; padding: 0;">
        <tr>
            <td>
                <center>
                    <br />
                    <table  cellpadding="0" cellspacing="0" style="border: 0; margin: 0; padding: 0;">
                        <tbody>
                            <div style="background-color: #C6DEBD; width: 100vw; height: 75px; display: flex; align-items: center;">
                                <p style="font-size: 24px; width: 100vw; font-weight: 700; font-family: 'Arial', sans-serif; text-align: center; color: #ffffff;">QR-транизит</p>
                            </div>
                        </tbody>
                    </table>
                    <table  cellpadding="0" cellspacing="0" style="padding: 25px; background-color: #ccc; width: 100vw; border: 0; margin: 0;">
                        <tbody">
                            <div style="width: 100%; background-color: #ffffff; border-radius: 20px;">
                                <img src="https://iv.kommersant.ru/Issues.photo/REGIONS/KRASNODAR_Tema/2022/201/KKD_000004_09100_1_t218_161124.jpg" alt="Автобусы" style="border: 0; width: 100%; height: 200px; display: block; border-radius: 20px;">
                                <div style="padding: 5px 10px;" >
                                    <p style="font-size: 28px; font-weight: 600; font-family: 'Arial', sans-serif; width: 100%; text-align: center;">Добро пожаловать!</p>
                                    <p style="font-size: 20px; font-weight: 500; font-family: 'Arial', sans-serif; width: 100%;">Вы получили приглашение от Агента. Перейдя по ссылке ниже, вы подтверждаете, что являетесь перевозчиком. <br><br> Если это письмо вы получили по ошибке, то проинорируйте его.</p>
                                    <div style="width: 100%; margin: 20px 0;">
                                        <center>
                                            <div style="background-color: #ccc; border-radius: 10px; padding: 10px 20px; border: 1px solid #929292; width: 100px;">
                                                <a href="" style="-webkit-text-size-adjust: none; color: #006aff; width: 100%; text-align: center;">http://link.com</a>
                                            </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </center>
            </td>
        </tr>
    </table>
</body>
</html>
"""

@bot.callback_query_handler(func=lambda call: True)
def callback_query(call):
    markup = types.InlineKeyboardMarkup()

    carriersRequest = requests.get('http://localhost/sbpticket.com/get-carriers.php')

    carriers = json.loads(carriersRequest.text)

    def filter_carriers(carrier):
        tag_parts = carrier['agentTag'].split('@')
        
        if len(tag_parts) > 1 and tag_parts[1] == call.from_user.username:
            return carrier
    

    filteredCarriers = filter(filter_carriers, carriers)

    if call.data == 'get_qr':
        photo = open('./img/qr.png', 'rb')
        
        button = types.InlineKeyboardButton("Скачать", callback_data="download_qr")
        markup.add(button)

        bot.send_photo(call.message.chat.id, photo=photo, reply_markup=markup)
    elif call.data == 'get_carriers':
        for carrier in filteredCarriers:
            button = types.InlineKeyboardButton(carrier["name"], callback_data=carrier["name"])
            markup.add(button)

        bot.send_message(call.message.chat.id, "Список ваших перевозчиков", reply_markup=markup)
    elif call.data == 'create_carriers':
        bot.send_message(call.message.chat.id, 'Введите адрес электронной почты перевозчика через команду')

        @bot.message_handler(content_types=['text'])
        def message_input_step(message):
            global text 
            text = message.text
            bot.register_next_step_handler(message, smtp.send_email(text, 'Письмо приглашение', html_template))
    else:
        for carrier in carriers:
            if carrier["name"] == call.data:
                for bus in buses:
                    if bus['carrierEmail'] == carrier['email']:
                        button = types.InlineKeyboardButton(str(bus['routes']), callback_data="get_qr")
                        markup.add(button)
                
                bot.send_message(call.message.chat.id, "Список транспорта, который пренадлежит перевозщику", reply_markup=markup)


bot.infinity_polling()