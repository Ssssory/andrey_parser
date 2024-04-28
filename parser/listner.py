import pika, sys, os

def main():
    credentials = pika.PlainCredentials('admin', 'pass')
    connection = pika.BlockingConnection(pika.ConnectionParameters(
        host='rabbitmq',
        credentials=credentials,
        virtual_host='/'))  # Указывайте нужный vhost, если это не '/'
    channel = connection.channel()

    channel.queue_declare(queue='test', durable=True)

    def callback(ch, method, properties, body):
        print(" [x] Received %r" % body)

    channel.basic_consume(queue='test', on_message_callback=callback, auto_ack=True)

    print(' [*] Waiting for messages. To exit press CTRL+C')
    channel.start_consuming()

if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Interrupted')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)