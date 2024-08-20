import telebot
import requests
import json
from telebot import types

bot = telebot.TeleBot('6896254624:AAHllGvmvCFgBekMvv4P8y7EOP1agbHWAAk', parse_mode=None)

@bot.message_handler(commands=['start'])
def send_welcome(message):
	response = requests.get('http://localhost/sbpticket.com/get-registr-items.php')

	drivers = json.loads(response.text)[0]
     
	userTag = message.from_user.username
	
	global isDriver
	isDriver = False

	for driver in drivers:
		if driver['tgName'].split('@')[1] == userTag:
			isDriver = True
			break
			
	if isDriver:
		markup = types.InlineKeyboardMarkup()
		button1 = types.InlineKeyboardButton("Взять смену", callback_data="get_job")
		markup.add(button1)
		bot.send_message(message.chat.id, "Привет, {0.first_name}! \n \nТы вышел на новую смену. \n \nДля того, чтобы начать работу нажми на кнопку внизу.\n\nСчастливого пути‼".format(message.from_user), reply_markup=markup)
	else:
		bot.send_message(message.chat.id, 'Вы не являетесь водителем')

@bot.callback_query_handler(func=lambda call: True)
def callback_query(call):
    if call.data == 'get_job':
        bot.send_message(call.message.chat.id, "Вы взяли смену")


bot.infinity_polling()
