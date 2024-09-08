import peewee as orm


db = orm.SqliteDatabase('links.db')


class AdLink(orm.Model):
    url =  orm.CharField()

    class Meta:
        database = db
        legacy_table_names = False


APP_MODELS = [AdLink]
