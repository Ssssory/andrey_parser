import data.models as model


def init_schema():
    Schema()


class Schema:
    def __init__(self):
        self._create_tables()

    def _create_tables(self):
        with model.db.connection_context():
            tables = model.db.get_tables()
            #initially only one table in sqlite: 'sqlite_sequence'
            if not tables:
                model.db.create_tables(model.APP_MODELS)


class AdLinkRepo:
    def bulk_insert(self, urls):
        with model.db.atomic():
            return model.AdLink.insert_many(urls).execute()

    def find_all(self):
        return model.AdLink.select()
